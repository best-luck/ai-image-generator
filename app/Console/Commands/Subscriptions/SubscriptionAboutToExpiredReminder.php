<?php

namespace App\Console\Commands\Subscriptions;

use App\Models\Subscription;
use App\Notifications\Subscriptions\SubscriptionAboutToExpiredNotification;
use Illuminate\Console\Command;

class SubscriptionAboutToExpiredReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expiring-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to users whose subscriptions are about to expire';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (mailTemplate('subscription_about_expired')->status) {
            $subscriptions = Subscription::notFree()->isAboutToExpire()->aboutToExpireReminderNotSent()->get();
            if ($subscriptions->count() > 0) {
                foreach ($subscriptions as $subscription) {
                    $subscription->user->notify(new SubscriptionAboutToExpiredNotification($subscription));
                    $subscription->update(['about_to_expire_reminder' => true]);
                }
            }
        }
    }
}