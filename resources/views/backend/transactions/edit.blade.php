@extends('backend.layouts.grid')
@section('title', admin_lang('Transaction | #') . $transaction->id)
@section('back', route('admin.transactions.index'))
@section('content')
    @if ($transaction->isCancelled())
        <div class="alert bg-danger text-white">
            <p class="mb-0">{{ admin_lang('Transaction has been canceled') }}</p>
        </div>
    @endif
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card custom-card">
                <div class="card-header bg-primary text-white">
                    {{ admin_lang('Transaction details') }}
                </div>
                <ul class="custom-list-group list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span><strong>{{ admin_lang('Transaction ID') }}</strong></span>
                        <span>#{{ $transaction->id }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span><strong>{{ admin_lang('User') }}</strong></span>
                        <span><a href="{{ route('admin.users.edit', $transaction->user->id) }}" class="text-dark"><i
                                    class="fa fa-user me-2"></i>
                                {{ $transaction->user->name }}
                            </a></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span><strong>{{ admin_lang('Plan Name') }}</strong></span>
                        <span><a href="{{ route('admin.plans.edit', $transaction->plan->id) }}"><i
                                    class="far fa-gem me-2"></i>
                                {{ $transaction->plan->name }}
                            </a></span>
                    </li>
                    @if ($transaction->gateway)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <span><strong>{{ admin_lang('Payment Gateway') }}</strong></span>
                            <span><a href="{{ route('admin.settings.gateways.edit', $transaction->gateway->id) }}"
                                    class="text-dark"><i
                                        class="fas fa-external-link-alt me-2"></i>{{ $transaction->gateway->name }}</a></span>
                        </li>
                    @endif
                    @if ($transaction->payment_id)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <span><strong>{{ admin_lang('Payment ID') }}</strong></span>
                            <span>{{ $transaction->payment_id }}</span>
                        </li>
                    @endif
                    @if ($transaction->payer_id)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <span><strong>{{ admin_lang('Payer ID') }}</strong></span>
                            <span>{{ $transaction->payer_id }}</span>
                        </li>
                    @endif
                    @if ($transaction->payer_email)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <span><strong>{{ admin_lang('Payer Email') }}</strong></span>
                            <span>{{ $transaction->payer_email }}</span>
                        </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span><strong>{{ admin_lang('Transaction Type') }}</strong></span>
                        <span>
                            @if ($transaction->type == 1)
                                <span class="badge bg-lg-1">{{ admin_lang('Subscribe') }}</span>
                            @elseif($transaction->type == 2)
                                <span class="badge bg-lg-2">{{ admin_lang('Renew') }}</span>
                            @elseif($transaction->type == 3)
                                <span class="badge bg-lg-9">{{ admin_lang('Upgrade') }}</span>
                            @elseif($transaction->type == 4)
                                <span class="badge bg-lg-10">{{ admin_lang('Downgrade') }}</span>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span><strong>{{ admin_lang('Transaction Status') }}</strong></span>
                        @if ($transaction->isPaid())
                            @if ($transaction->total > 0)
                                <span class="badge bg-success">{{ lang('Paid', 'account') }}</span>
                            @else
                                <span class="badge bg-primary">{{ lang('Done', 'account') }}</span>
                            @endif
                        @else
                            <span class="badge bg-danger">{{ lang('Cancelled', 'account') }}</span>
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span><strong>{{ admin_lang('Transaction date') }}</strong></span>
                        <span><strong>{{ dateFormat($transaction->created_at) }}</strong></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card custom-card mb-3">
                <div class="card-header bg-secondary text-white">
                    {{ admin_lang('Payment details') }}
                </div>
                <ul class="custom-list-group list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span><strong>{{ admin_lang('Plan Price') }}</strong></span>
                        <span><strong>{{ priceSymbol($transaction->details_before_discount->price) }}</strong></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span><strong>{{ admin_lang('Taxes') }}</strong></span>
                        <span><strong>+{{ priceSymbol($transaction->details_before_discount->tax) }}</strong></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span><strong>{{ admin_lang('Subtotal') }}</strong></span>
                        <span><strong>{{ priceSymbol($transaction->details_before_discount->total) }}</strong></span>
                    </li>
                </ul>
            </div>
            @if ($transaction->details_after_discount || $transaction->gateway)
                <div class="card custom-card mb-3">
                    <ul class="custom-list-group list-group list-group-flush">
                        @if ($transaction->details_after_discount)
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <span><strong>{{ admin_lang('Discount') }}</strong>
                                    @if ($transaction->coupon_id)
                                        (<a
                                            href="{{ route('admin.coupons.edit', $transaction->coupon_id) }}">{{ admin_lang('View Coupon') }}</a>)
                                    @endif
                                </span>
                                <span
                                    class="text-danger"><strong>-{{ priceSymbol($transaction->details_before_discount->total - $transaction->details_after_discount->total) }}</strong></span>
                            </li>
                        @endif
                        @if ($transaction->gateway)
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <span><strong>{{ admin_lang('Gateway Fees') }}</strong></span>
                                <span><strong>+{{ priceSymbol($transaction->fees) }}</strong></span>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
            <div class="card custom-card mb-3">
                <ul class="custom-list-group list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span>
                            <h5 class="mb-0"><strong>{{ admin_lang('Total') }}</strong></h5>
                        </span>
                        <span>
                            <h5 class="mb-0">
                                <strong>{{ priceSymbol($transaction->total) }}</strong>
                            </h5>
                        </span>
                    </li>
                </ul>
            </div>
            @if ($transaction->isPaid())
                <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-danger btn-lg w-100 vironeer-able-to-delete">
                        <i class="far fa-times-circle"></i>
                        <span>{{ admin_lang('Cancel Transaction') }}</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
