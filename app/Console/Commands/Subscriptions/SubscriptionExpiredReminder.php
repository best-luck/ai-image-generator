<?php

namespace App\Console\Commands\Subscriptions;

use App\Models\Subscription;
use App\Notifications\Subscriptions\SubscriptionExpiredNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionExpiredReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expired-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to users whose subscriptions are expired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (mailTemplate('subscription_expired')->status) {
            $subscriptions = Subscription::where('expiry_at', '<=', Carbon::now()->subDays(settings('subscription')->expired_reminder))
                ->where('status', Subscription::STATUS_ACTIVE)
                ->notFree()->expiredReminderNotSent()->get();
            if ($subscriptions->count() > 0) {
                foreach ($subscriptions as $subscription) {
                    $subscription->user->notify(new SubscriptionExpiredNotification($subscription));
                    $subscription->update(['expired_reminder' => true]);
                }
            }
        }
    }
}