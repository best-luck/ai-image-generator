<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unviewedTransactions = Transaction::where('is_viewed', 0)->whereIn('status', [2, 3])->get();
        if ($unviewedTransactions->count() > 0) {
            foreach ($unviewedTransactions as $unviewedTransaction) {
                $unviewedTransaction->is_viewed = true;
                $unviewedTransaction->save();
            }
        }
        $paidTransactions = Transaction::paid()->with(['user', 'plan', 'gateway'])->get();
        $canceledTransactions = Transaction::cancelled()->with(['user', 'plan', 'gateway'])->get();
        $paidAmountQuery = Transaction::paid();
        $paidAmount = [
            'total' => $paidAmountQuery->sum('total'),
            'subscriptions' => $paidAmountQuery->sum('price'),
            'taxes' => $paidAmountQuery->sum('tax'),
            'fees' => $paidAmountQuery->sum('fees'),
        ];
        $canceledAmountQuery = Transaction::cancelled();
        $canceledAmount = [
            'total' => $canceledAmountQuery->sum('price'),
            'subscriptions' => $canceledAmountQuery->sum('price'),
            'taxes' => $canceledAmountQuery->sum('price'),
            'fees' => $canceledAmountQuery->sum('fees'),
        ];
        return view('backend.transactions.index', [
            'paidTransactions' => $paidTransactions,
            'canceledTransactions' => $canceledTransactions,
            'paidAmount' => $paidAmount,
            'canceledAmount' => $canceledAmount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        abort_if($transaction->isPending() || $transaction->isUnpaid(), 404);
        return view('backend.transactions.edit', ['transaction' => $transaction]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->status != 2) {
            toastr()->error(admin_lang('Transaction cannot be canceled'));
            return back();
        }
        $updateTransaction = $transaction->update(['status' => 3]);
        if ($updateTransaction) {
            toastr()->success(admin_lang('Transaction Canceled Successfully'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        toastr()->success(admin_lang('Deleted Successfully'));
        return back();
    }
}
