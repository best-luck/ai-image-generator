<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUnpaidTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:unpaid-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unpaid transactions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transactions = Transaction::where('created_at', '<=', Carbon::now()->subHour())->whereIn('status', [0, 1])->get();
        if ($transactions->count() > 0) {
            foreach ($transactions as $transaction) {
                $transaction->delete();
            }
        }
    }
}