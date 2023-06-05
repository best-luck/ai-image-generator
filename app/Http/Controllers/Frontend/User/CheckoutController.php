<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Coupon;
use App\Models\PaymentGateway;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CheckoutController extends Controller
{

    const STATUS_UNPAID = 0;
    const STATUS_PENDING = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCELLED = 3;

    public function index($checkout_id)
    {
        try {
            $transaction = Transaction::where(['checkout_id' => $checkout_id, 'user_id' => userAuthInfo()->id])->unpaid()->firstOrFail();
        } catch(\Exception $e) {
            $user = Auth::user();
            $ip = ipinfo()->ip;
            if ($user)
                $transaction = Payment::where([['checkout_id', $checkout_id], ['user_id', userAuthInfo()->id]])->unpaid()->firstOrFail();
            else {
                $transaction = Payment::where(['checkout_id' => $checkout_id, 'ip' => $ip])->unpaid()->firstOrFail();
            }
        }
        $paymentGateways = PaymentGateway::where([['status', 1], ['min', '<=', $transaction->total]])->hasCurrency()->get();
        return view('frontend.user.checkout', [
            'user' => userAuthInfo(),
            'transaction' => $transaction,
            'paymentGateways' => $paymentGateways,
        ]);
    }

    public function isUnpaid()
    {
        return $this->status == self::STATUS_UNPAID;
    }

    public function applyCoupon(Request $request, $checkout_id)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => ['required', 'string', 'max:20'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $ip = ipInfo()->ip;
        $user = Auth::user();
        if ($user) {
            $transaction = Transaction::where([['checkout_id', $checkout_id], ['user_id', userAuthInfo()->id], ['coupon_id', null], ['total', '!=', 0]])->unpaid()->firstOrFail();
            $coupon = Coupon::validCode($request->coupon_code)->validForPlan($transaction->plan->id)->first();
        } else {
            $freePlan = Plan::free()->get()->first();
            $transaction = Payment::where([['checkout_id', $checkout_id], ['ip', $ip], ['coupon_id', null], ['total', '!=', 0]])->unpaid()->firstOrFail();
            $coupon = Coupon::validCode($request->coupon_code)->validForPlan($freePlan->id)->first();
        }
        
        if (!$coupon) {
            toastr()->error(lang('Invalid or expired coupon code', 'checkout'));
            return back()->withInput();
        }
        if ($coupon->action_type != 0) {
            if ($transaction->type != $coupon->action_type) {
                toastr()->error(lang('Invalid or expired coupon code', 'checkout'));
                return back()->withInput();
            }
        }
        try {
            $couponTransactionsCount = Transaction::where([['coupon_id', $coupon->id], ['user_id', userAuthInfo()->id]])->whereIn('status', [0, 2])->count();
        } catch (\Exception $e) {
            $couponTransactionsCount = Payment::where([['coupon_id', $coupon->id], ['ip', $ip]])->whereIn('status', [0, 2])->count();
        }
        if ($couponTransactionsCount >= $coupon->limit) {
            toastr()->error(lang('You have exceeded the usage limit for this coupon', 'checkout'));
            return back()->withInput();
        }
        $planPriceAfterDiscount = ($transaction->price - ($transaction->price * $coupon->percentage) / 100);
        $taxPriceAfterDiscount = ($planPriceAfterDiscount * countryTax(userAuthInfo()->address->country ?? ipInfo()->location->country)) / 100;
        $totalPriceAfterDiscount = ($planPriceAfterDiscount + $taxPriceAfterDiscount);
        $detailsAfterDiscount = [
            'price' => priceFormt($planPriceAfterDiscount),
            'tax' => priceFormt($taxPriceAfterDiscount),
            'total' => priceFormt($totalPriceAfterDiscount),
        ];
        $updateTransaction = $transaction->update([
            'coupon_id' => $coupon->id,
            'details_after_discount' => $detailsAfterDiscount,
            'price' => $planPriceAfterDiscount,
            'tax' => $taxPriceAfterDiscount,
            'total' => $totalPriceAfterDiscount,
        ]);
        if ($updateTransaction) {
            toastr()->success(lang('Coupon has been applied successfully', 'checkout'));
            return back();
        }
    }

    public function removeCoupon(Request $request, $checkout_id)
    {
        $ip = ipInfo()->ip;
        $user = Auth::user();
        if ($user) {
            $transaction = Transaction::where([['checkout_id', $checkout_id], ['user_id', userAuthInfo()->id], ['coupon_id', '!=', null]])->unpaid()->firstOrFail();
        } else {
            $transaction = Payment::where(['checkout_id' => $checkout_id, 'ip' => $ip])->unpaid()->firstOrFail();
        }
        
        $updateTransaction = $transaction->update([
            'coupon_id' => null,
            'details_after_discount' => null,
            'price' => $transaction->details_before_discount->price,
            'tax' => $transaction->details_before_discount->tax,
            'total' => $transaction->details_before_discount->total,
        ]);
        if ($updateTransaction) {
            return back();
        }
    }

    public function proccess(Request $request, $checkout_id)
    {
        // $validator = Validator::make($request->all(), [
        //     'address_1' => ['required', 'string', 'max:255'],
        //     'address_2' => ['nullable', 'string', 'max:255'],
        //     'city' => ['required', 'string', 'max:150'],
        //     'state' => ['required', 'string', 'max:150'],
        //     'zip' => ['required', 'string', 'max:100'],
        //     'country' => ['required', 'integer', 'exists:countries,id'],
        // ]);
        // if ($validator->fails()) {
        //     foreach ($validator->errors()->all() as $error) {
        //         toastr()->error($error);
        //     }
        //     return back();
        // }
        try {
            $transaction = Transaction::where([['checkout_id', $checkout_id], ['user_id', userAuthInfo()->id]])->unpaid()->firstOrFail();
        } catch(\Exception $e) {
            $user = Auth::user();
            $ip = ipinfo()->ip;
            if ($user)
                $transaction = Payment::where([['checkout_id', $checkout_id], ['user_id', userAuthInfo()->id]])->unpaid()->firstOrFail();
            else {
                $transaction = Payment::where(['checkout_id' => $checkout_id, 'ip' => $ip])->unpaid()->firstOrFail();
            }
        }
        if ($transaction->coupon_id) {
            if (!$transaction->coupon || $transaction->coupon->isExpiry()) {
                toastr()->error(lang('Invalid or expired coupon code', 'checkout'));
                return back()->withInput();
            }
        }
        if ($transaction->total != 0) {
            $paymentGateway = PaymentGateway::where([['id', $request->payment_method]])->hasCurrency()->active()->first();
            if (!$paymentGateway || $transaction->total < $paymentGateway->min) {
                toastr()->error(lang('Selected payment method is not active', 'checkout'));
                return back();
            }
        }
        $country = Country::find($request->country);
        $address = [
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $country->name,
        ];
        $user = Auth::user();
        if ($user) {
            $updateUserAddress = $user->update(['address' => $address]);
        }
        if ($transaction->total == 0) {
            $transaction->update(['status' => 2]);
            $this->updateSubscription($transaction);
            toastr()->success(lang('Subscribed Successfully', 'checkout'));
            return redirect()->route('user.settings.subscription');
        }
        $paymentHandler = $paymentGateway->handler;
        $paymentData = $paymentHandler::process($transaction);
        $paymentData = json_decode($paymentData);
        if ($paymentData->error == true) {
            toastr()->error($paymentData->msg);
            return back();
        }
        $updateTransaction = $transaction->update(['status' => 1]);
        if ($updateTransaction) {
            if (isset($paymentData->redirectUrl)) {
                return redirect($paymentData->redirectUrl);
            }
            return view($paymentData->view, [
                'details' => $paymentData->details,
                'trx' => $paymentData->trx,
            ]);
        }
    }

    public static function updateSubscription($transaction)
    {
        if ($transaction->status != 2) {
            throw new Exception(lang('Incomplete payment', 'checkout'));
        }
        if ($transaction->type == 1) {
            $expiry_at = ($transaction->plan->interval == 1) ? Carbon::now()->addMonth() : Carbon::now()->addYear();
            $subscription = new Subscription();
            $subscription->user_id = $transaction->user_id;
            $subscription->plan_id = $transaction->plan_id;
            $subscription->expiry_at = $expiry_at;
            $subscription->save();
        }
        if ($transaction->type == 2) {
            $subscription = $transaction->user->subscription;
            if ($transaction->plan->interval == 1) {
                if ($subscription->isExpired()) {
                    $expiry_at = Carbon::now()->addMonth();
                } else {
                    $expiry_at = Carbon::parse($subscription->expiry_at)->addMonth();
                }
            } else {
                if ($subscription->isExpired()) {
                    $expiry_at = Carbon::now()->addYear();
                } else {
                    $expiry_at = Carbon::parse($subscription->expiry_at)->addYear();
                }
            }
            $subscription->expiry_at = $expiry_at;
            $subscription->generated_images = 0;
            $subscription->about_to_expire_reminder = false;
            $subscription->expired_reminder = false;
            $subscription->update();
        }
        if ($transaction->type == 3 || $transaction->type == 4) {
            $subscription = $transaction->user->subscription;
            $expiry_at = ($transaction->plan->interval == 1) ? Carbon::now()->addMonth() : Carbon::now()->addYear();
            $subscription->plan_id = $transaction->plan_id;
            $subscription->expiry_at = $expiry_at;
            $subscription->generated_images = 0;
            $subscription->about_to_expire_reminder = false;
            $subscription->expired_reminder = false;
            $subscription->update();
        }
    }
}