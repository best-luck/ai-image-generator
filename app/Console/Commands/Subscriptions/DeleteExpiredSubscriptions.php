<?php

namespace App\Console\Commands\Subscriptions;

use App\Models\Subscription;
use App\Notifications\Subscriptions\SubscriptionDeletedNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expired-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired subscriptions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = settings('subscription')->delete_expired;
        $subscriptions = Subscription::where([['expiry_at', '<', Carbon::now()->subDays($days)], ['status', Subscription::STATUS_ACTIVE]])->notFree()->get();
        if ($subscriptions->count() > 0) {
            foreach ($subscriptions as $subscription) {
                if ($subscription->user->generated_images->count() > 0) {
                    foreach ($subscription->user->generated_images as $generatedImage) {
                        $handler = $generatedImage->storageProvider->handler;
                        $delete = $handler::delete($generatedImage->path);
                        $generatedImage->delete();
                    }
                }
                $subscription->user->notify(new SubscriptionDeletedNotification($subscription));
                $subscription->delete();
            }
        }
    }
}