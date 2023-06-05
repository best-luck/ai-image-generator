<?php

namespace App\Http\Controllers\Frontend\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\User\CheckoutController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Mollie\Laravel\Facades\Mollie;

class MollieController extends Controller
{

    public static function process($trx)
    {
        if ($trx->status != 0) {
            $data['error'] = true;
            $data['msg'] = lang('Invalid or expired transaction', 'checkout');
            return json_encode($data);
        }
        $planInterval = ($trx->plan->interval == 1) ? '(Monthly)' : '(Yearly)';
        config(['mollie.key' => trim(paymentGateway('mollie')->credentials->api_key)]);
        $paymentName = "Payment for subscription " . $trx->plan->name . " Plan " . $planInterval;
        $gatewayFees = ($trx->total * paymentGateway('mollie')->fees) / 100;
        $totalPrice = priceFormt(($trx->total + $gatewayFees));
        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => settings('currency')->code,
                "value" => $totalPrice,
            ],
            "description" => $paymentName,
            "redirectUrl" => route('ipn.mollie') . '?checkoutId=' . $trx->checkout_id,
            "metadata" => [
                "order_id" => $trx->id,
            ],
        ]);
        try {
            $payment = Mollie::api()->payments()->get($payment->id);
            $trx->update(['fees' => $gatewayFees, 'payment_id' => $payment->id]);
            $data['error'] = false;
            $data['redirectUrl'] = $payment->getCheckoutUrl();
            return json_encode($data);
        } catch (\Exception$e) {
            $data['error'] = true;
            $data['msg'] = $e->getMessage();
            return json_encode($data);
        }
    }

    public function ipn(Request $request)
    {
        $checkoutId = $request->checkoutId;
        try {
            $trx = Transaction::where([['user_id', userAuthInfo()->id], ['checkout_id', $checkoutId], ['payment_id', '!=', null]])->pending()->first();
            if (is_null($trx)) {
                toastr()->error(lang('Invalid or expired transaction', 'checkout'));
                return redirect()->route('user.settings.subscription');
            }
            config(['mollie.key' => trim(paymentGateway('mollie')->credentials->api_key)]);
            $payment = Mollie::api()->payments()->get($trx->payment_id);
            if ($payment->metadata->order_id != $trx->id) {
                toastr()->error(lang('Invalid or expired transaction', 'checkout'));
                return redirect()->route('user.settings.subscription');
            }
            if ($payment->status == "paid") {
                $total = ($trx->total + $trx->fees);
                $payment_gateway_id = paymentGateway('mollie')->id;
                $payment_id = $payment->id;
                $updateTrx = $trx->update([
                    'total' => $total,
                    'payment_gateway_id' => $payment_gateway_id,
                    'payment_id' => $payment_id,
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