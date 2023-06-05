<?php

namespace App\Console\Commands\Subscriptions;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RenewFreeSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:renew-free';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renewing the free subscriptions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscriptions = Subscription::where('expiry_at', '>=', Carbon::now()->subHour())->free()->active()->get();
        if ($subscriptions->count() > 0) {
            foreach ($subscriptions as $subscription) {
                $expiry_at = ($subscription->plan->interval == 1) ? Carbon::parse($subscription->expiry_at)->addMonth() : Carbon::parse($subscription->expiry_at)->addYear();
                $subscription->expiry_at = $expiry_at;
                $subscription->update();
            }
        }
    }
}