<?php

namespace App\Listeners;

use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;

class CreateFreeSubscription
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $freePlan = Plan::free()->first();
        if ($freePlan) {
            $user = $event->user;
            $expiry_at = ($freePlan->interval == 1) ? Carbon::now()->addMonth() : Carbon::now()->addYear();
            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->plan_id = $freePlan->id;
            $subscription->expiry_at = $expiry_at;
            $subscription->save();
        }
    }
}