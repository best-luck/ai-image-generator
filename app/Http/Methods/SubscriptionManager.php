<?php

namespace App\Http\Methods;

use App\Models\GeneratedImage;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class SubscriptionManager
{
    public static function subscription()
    {
        if (Auth::user()) {
            $subscription = self::users();
        } else {
            $subscription = self::guests();
        }
        return array_to_object($subscription);
    }

    private static function users()
    {
        $user = userAuthInfo();
        if ($user->isSubscribed()) {
            $subscription['is_subscribed'] = $user->subscription->isActive() ? true : ($user->subscription->isFree() ? true : false);
            $subscription['plan'] = $user->subscription->plan;
            $subscription['generated_images'] = $user->subscription->generated_images;
            $subscription['remaining_images'] = ($user->subscription->plan->images - $user->subscription->generated_images);
        } else {
            return self::unsubscribed();
        }
        return $subscription;
    }

    private static function guests()
    {
        $subscription = null;
        $plan = Plan::forGuests()->first();
        if ($plan) {
            $generatedImages = GeneratedImage::where('ip', ipInfo()->ip)->guests()->get()->count();
            $subscription['is_subscribed'] = true;
            $subscription['plan'] = $plan;
            $subscription['generated_images'] = $generatedImages;
            $subscription['remaining_images'] = ($plan->images - $generatedImages);
        }
        if (extension('trustip')->status && isIpProxy(ipInfo()->ip)) {
            $subscription = null;
        }
        return $subscription;
    }

    private static function unsubscribed()
    {
        $subscription['is_subscribed'] = false;
        $subscription['generated_images'] = 0;
        $subscription['remaining_images'] = 0;
        return $subscription;
    }

}