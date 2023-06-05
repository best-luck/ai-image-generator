<?php

namespace App\Http\Controllers\Frontend\Gateways;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\User\CheckoutController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{

    public static function process($trx)
    {
        if ($trx->status != 0) {
            $data['error'] = true;
            $data['msg'] = lang('Invalid or expired transaction', 'checkout');
            return json_encode($data);
        }
        $planInterval = ($trx->plan->interval == 1) ? '(Monthly)' : '(Yearly)';
        $paymentName = "Payment for subscription " . $trx->plan->name . " Plan " . $planInterval;
        $gatewayFees = ($trx->total * paymentGateway('razorpay')->fees) / 100;
        $totalPrice = round(($trx->total + $gatewayFees), 2);
        $priceIncludeFees = str_replace('.', '', ($totalPrice * 100));
        try {
            $api = new Api(paymentGateway('razorpay')->credentials->key_id, paymentGateway('razorpay')->credentials->key_secret);
            $order = $api->order->create([
                'receipt' => $trx->id,
                'amount' => $priceIncludeFees,
                'currency' => settings('currency')->code,
                'payment_capture' => '0',
            ]);
            $details = [
                'key' => paymentGateway('razorpay')->credentials->key_id,
                'amount' => $priceIncludeFees,
                'currency' => settings('currency')->code,
                'order_id' => $order['id'],
                'buttontext' => lang('Pay Now', 'checkout'),
                'name' => settings('general')->site_name,
                'description' => $paymentName,
                'image' => asset(settings('media')->dark_logo),
                'prefill.name' => userAuthInfo()->name,
                'prefill.email' => userAuthInfo()->email,
                'prefill.contact' => userAuthInfo()->mobile,
                'theme.color' => settings('colors')->primary_color,
            ];
            $data['error'] = false;
            $data['trx'] = $trx;
            $data['details'] = $details;
            $data['view'] = "frontend.user.gateways." . paymentGateway('razorpay')->alias;
            $trx->update(['fees' => $gatewayFees, 'payment_id' => $order['id']]);
            return json_encode($data);
        } catch (\Exception$e) {
            $data['error'] = true;
            $data['msg'] = $e->getMessage();
            return json_encode($data);
        }
    }

    public function ipn(Request $request)
    {
        $checkoutId = $request->checkout_id;
        $paymentId = $request->razorpay_order_id;
        try {
            $trx = Transaction::where([['checkout_id', $checkoutId], ['payment_id', $paymentId]])->pending()->first();
            if (is_null($trx)) {
                toastr()->error(lang('Payment failed', 'checkout'));
                return redirect()->route('user.settings.subscription');
            }
            $signature = hash_hmac('sha256', $request->razorpay_order_id . "|" . $request->razorpay_payment_id, paymentGateway('razorpay')->credentials->key_secret);
            if ($signature == $request->razorpay_signature) {
                $total = ($trx->total + $trx->fees);
                $payment_gateway_id = paymentGateway('razorpay')->id;
                $payment_id = $request->razorpay_payment_id;
                $updateTrx = $trx->update([
                    'total' => $total,
                    'payment_gateway_id' => $payment_gateway_id,
                    'payment_id' => $payment_id,
                    'status' => 2,
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