<?php

namespace App\Http\Controllers\Frontend\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\User\CheckoutController;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalExpressController extends Controller
{
    public static function apiContext()
    {
        $clientId = paymentGateway('paypal_express')->credentials->client_id;
        $clientSecret = paymentGateway('paypal_express')->credentials->client_secret;
        $apiContext = new ApiContext(new OAuthTokenCredential($clientId, $clientSecret));
        if (!paymentGateway('paypal_express')->test_mode) {
            $apiContext->setConfig(['mode' => 'live']);
        }
        return $apiContext;
    }

    public static function process($trx)
    {
        if ($trx->status != 0) {
            $data['error'] = true;
            $data['msg'] = lang('Invalid or expired transaction', 'checkout');
            return json_encode($data);
        }

        $planInterval = ($trx->plan->interval == 1) ? '(Monthly)' : '(Yearly)';

        $paymentName = "Payment for subscription " . $trx->plan->name . " Plan " . $planInterval;
        $gatewayFees = ($trx->total * paymentGateway('paypal_express')->fees) / 100;
        $priceIncludeFees = ($trx->total + $gatewayFees);

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName($paymentName)
            ->setCurrency(settings('currency')->code)
            ->setQuantity(1)
            ->setSku($trx->id)
            ->setPrice(round($trx->price, 2));

        $itemList = new ItemList();
        $itemList->setItems([$item1]);

        $details = new Details();
        $details->setShipping(0)
            ->setTax(round($trx->tax, 2))
            ->setHandlingFee(round($gatewayFees, 2))
            ->setSubtotal(round($trx->price, 2));

        $amount = new Amount();
        $amount->setCurrency(settings('currency')->code)
            ->setTotal(round($priceIncludeFees, 2))
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($paymentName)
            ->setInvoiceNumber($trx->id);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('ipn.paypal_express'))
            ->setCancelUrl(route('user.settings.subscription'));

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create(self::apiContext());
            $data['error'] = false;
            $data['redirectUrl'] = $payment->getApprovalLink();
            return json_encode($data);
        } catch (\Exception$e) {
            $data['error'] = true;
            $data['msg'] = $e->getMessage();
            return json_encode($data);
        }

    }

    public function ipn(Request $request)
    {
        $paymentId = $request->paymentId;
        $PayerID = $request->PayerID;
        try {
            $payment = Payment::get($paymentId, self::apiContext());
            $execution = new PaymentExecution();
            $execution->setPayerId($PayerID);
            $total = $payment->transactions[0]->amount->total;
            $subtotal = $payment->transactions[0]->amount->details->subtotal;
            $tax = $payment->transactions[0]->amount->details->tax;
            $handling_fee = $payment->transactions[0]->amount->details->handling_fee;
            $currency = $payment->transactions[0]->amount->currency;
            $invoice_number = $payment->transactions[0]->invoice_number;
            $trx = \App\Models\Transaction::where([['user_id', userAuthInfo()->id], ['id', $invoice_number]])->pending()->first();
            if (is_null($trx)) {
                toastr()->error(lang('Invalid or expired transaction', 'checkout'));
                return redirect()->route('user.settings.subscription');
            }
            $details = new Details();
            $details->setShipping(0)->setTax($tax)->setHandlingFee($handling_fee)->setSubtotal($subtotal);
            $amount = new Amount();
            $amount->setCurrency($currency);
            $amount->setTotal($total);
            $amount->setDetails($details);
            $transaction = new Transaction();
            $transaction->setAmount($amount);
            $execution->addTransaction($transaction);
            $result = $payment->execute($execution, self::apiContext());
            if ($result->state == "approved") {
                $payment_gateway_id = paymentGateway('paypal_express')->id;
                $payment_id = $result->id;
                $payer_id = $result->payer->payer_info->payer_id;
                $payer_email = $result->payer->payer_info->email;
                $updateTrx = $trx->update([
                    'fees' => $handling_fee,
                    'total' => $total,
                    'payment_gateway_id' => $payment_gateway_id,
                    'payment_id' => $payment_id,
                    'payer_id' => $payer_id,
                    'payer_email' => $payer_email,
                    'status' => \App\Models\Transaction::STATUS_PAID,
                ]);
                if ($updateTrx) {
                    CheckoutController::updateSubscription($trx);
                    toastr()->success(lang('Payment made successfully', 'checkout'));
                    return redirect()->route('user.settings.subscription');
                }
            } else {
                toastr()->error(lang('Payment failed', 'checkout'));
                return redirect()->route('user.settings.subscription');
            }
        } catch (\Exception$e) {
            toastr()->error(lang('Payment failed', 'checkout'));
            return redirect()->route('user.settings.subscription');
        }
    }
}