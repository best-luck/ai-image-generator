<?php

namespace App\Http\Controllers\Frontend\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\User\CheckoutController;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;

class StripeCheckoutController extends Controller
{
    public static function process($trx)
    {
        if ($trx->status != 0) {
            $data['error'] = true;
            $data['msg'] = lang('Invalid or expired transaction', 'checkout');
            return json_encode($data);
        }

        if ($trx->plan) {
            $planInterval = ($trx->plan->interval == 1) ? '(Monthly)' : '(Yearly)';
            $paymentName = "Payment for subscription " . $trx->plan->name . " Plan " . $planInterval;
            $gatewayFees = ($trx->total * paymentGateway('stripe_checkout')->fees) / 100;
            $totalPrice = round(($trx->total + $gatewayFees), 2);
            $priceIncludeFees = str_replace('.', '', ($totalPrice * 100));
            $paymentDeatails = [
                'customer_creation' => 'always',
                'customer_email' => $trx->user->email,
                'payment_method_types' => [
                    'card',
                ],
                'line_items' => [[
                    'price_data' => [
                        'unit_amount' => $priceIncludeFees,
                        'currency' => settings('currency')->code,
                        'product_data' => [
                            'name' => settings('general')->site_name,
                            'description' => $paymentName,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'cancel_url' => route('user.settings.subscription'),
                'success_url' => route('ipn.stripe_checkout') . '?session_id={CHECKOUT_SESSION_ID}',
            ];
        } else {
            $paymentName = "Payment for image";
            $gatewayFees = ($trx->total * paymentGateway('stripe_checkout')->fees) / 100;
            $totalPrice = round(($trx->total + $gatewayFees), 2);
            $priceIncludeFees = str_replace('.', '', ($totalPrice * 100));
            $paymentDeatails = [
                'customer_creation' => 'always',
                'customer_email' => $trx->user_id != -1 ? $trx->user->email : "guest@user.com",
                'payment_method_types' => [
                    'card',
                ],
                'line_items' => [[
                    'price_data' => [
                        'unit_amount' => $priceIncludeFees,
                        'currency' => settings('currency')->code,
                        'product_data' => [
                            'name' => settings('general')->site_name,
                            'description' => $paymentName,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'cancel_url' => route('user.settings.subscription'),
                'success_url' => route('ipn.stripe_checkout') . '?session_id={CHECKOUT_SESSION_ID}',
            ];
        }
        
        try {
            Stripe::setApiKey(paymentGateway('stripe_checkout')->credentials->secret_key);
            $session = Session::create($paymentDeatails);
            if ($session) {
                $trx->update(['fees' => $gatewayFees, 'payment_id' => $session->id]);
                $data['error'] = false;
                $data['redirectUrl'] = $session->url;
                return json_encode($data);
            }
        } catch (\Exception$e) {
            $data['error'] = true;
            $data['msg'] = $e->getMessage();
            return json_encode($data);
        }
    }

    public function ipn(Request $request)
    {
        $session_id = $request->session_id;
        try {
            Stripe::setApiKey(paymentGateway('stripe_checkout')->credentials->secret_key);

            try {
                $trx = \App\Models\Transaction::where([['user_id', userAuthInfo()->id], ['payment_id', $session_id]])->pending()->first();
            } catch(\Exception $e) {
                $trx = \App\Models\Payment::where(['payment_id' => $session_id])->pending()->first();
            }

            if (is_null($trx)) {
                toastr()->error(lang('Invalid or expired transaction', 'checkout'));
                return redirect()->route('user.settings.subscription');
            }
            $session = Session::retrieve($session_id);
            if ($session->payment_status == "paid" && $session->status == "complete") {
                $customer = Customer::retrieve($session->customer);
                $total = ($trx->total + $trx->fees);
                $payment_gateway_id = paymentGateway('stripe_checkout')->id;
                $payment_id = $session->id;
                $payer_id = $customer->id;
                $payer_email = $customer->email;
                $updateTrx = $trx->update([
                    'total' => $total,
                    'payment_gateway_id' => $payment_gateway_id,
                    'payment_id' => $payment_id,
                    'payer_id' => $payer_id,
                    'payer_email' => $payer_email,
                    'status' => \App\Models\Transaction::STATUS_PAID,
                ]);
                if ($updateTrx && $trx->plan) {
                    CheckoutController::updateSubscription($trx);
                    toastr()->success(lang('Payment made successfully', 'checkout'));
                    return redirect()->route('user.settings.subscription');
                } 
                if ($updateTrx) {
                    $image = $trx->image;
                    return redirect()->route('images.show', hashid($image->id));
                }
            } else {
                toastr()->error(lang('Payment failed', 'checkout'));
                return redirect()->route('user.settings.subscription');
            }
        } catch (\Exception $e) {
            toastr()->error(lang('Payment failed', 'checkout'));
            return redirect()->route('user.settings.subscription');
        }
    }
}