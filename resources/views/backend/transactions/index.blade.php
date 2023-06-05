@extends('backend.layouts.grid')
@section('title', admin_lang('Transactions'))
@section('content')
    <div class="row g-3 mb-3 transactions">
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="counter-card-z c-light-green">
                <div class="counter-card-z-upper">
                    <div class="counter-card-z-meta">
                        <p class="counter-card-z-title">{{ admin_lang('Total Paid Amount') }}</p>
                        <h3 class="counter-card-z-counter-card">{{ priceSymbol($paidAmount['total']) }}</h3>
                    </div>
                    <div class="counter-card-z-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <hr>
                <div class="counter-card-z-lower">
                    <div class="details">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>{{ admin_lang('Subscriptions') }}</span>
                            <span>+ {{ priceSymbol($paidAmount['subscriptions']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>{{ admin_lang('Taxes') }}</span>
                            <span>+ {{ priceSymbol($paidAmount['taxes']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <span>{{ admin_lang('Fees') }}</span>
                            <span>+ {{ priceSymbol($paidAmount['fees']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="counter-card-z c-red">
                <div class="counter-card-z-upper">
                    <div class="counter-card-z-meta">
                        <p class="counter-card-z-title">{{ admin_lang('Total Canceled Amount') }}</p>
                        <h3 class="counter-card-z-counter-card">{{ priceSymbol($paidAmount['total']) }}</h3>
                    </div>
                    <div class="counter-card-z-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <hr>
                <div class="counter-card-z-lower">
                    <div class="details">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>{{ admin_lang('Subscriptions') }}</span>
                            <span>- {{ priceSymbol($canceledAmount['subscriptions']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>{{ admin_lang('Taxes') }}</span>
                            <span>- {{ priceSymbol($canceledAmount['taxes']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <span>{{ admin_lang('Fees') }}</span>
                            <span>- {{ priceSymbol($canceledAmount['fees']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card custom-card custom-tabs mb-3">
        <div class="card-body">
            <ul class="nav nav-pills" role="tablist">
                <li role="presentation">
                    <button class="nav-link active me-2" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid"
                        type="button" role="tab" aria-controls="paid" aria-selected="true">{{ admin_lang('Paid') }}
                        ({{ $paidTransactions->count() }})</button>
                </li>
                <li role="presentation">
                    <button class="nav-link" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled"
                        type="button" role="tab" aria-controls="canceled"
                        aria-selected="false">{{ admin_lang('Canceled') }}
                        ({{ $canceledTransactions->count() }})</button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card custom-card">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                <table class="datatable50 table w-100">
                    <thead>
                        <tr>
                            <th class="tb-w-2x">{{ admin_lang('#ID') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('User') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Plan') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Total') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Gateway') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Type') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('Created at') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paidTransactions as $transaction)
                            <tr>
                                <td><a
                                        href="{{ route('admin.transactions.edit', $transaction->id) }}">{{ $transaction->id }}</a>
                                </td>
                                <td><a href="{{ route('admin.users.edit', $transaction->user->id) }}" class="text-dark"><i
                                            class="fa fa-user me-2"></i>
                                        {{ $transaction->user->name }}
                                    </a>
                                </td>
                                <td><a href="{{ route('admin.plans.edit', $transaction->plan->id) }}"><i
                                            class="far fa-gem me-2"></i>
                                        {{ $transaction->plan->name }}
                                    </a>
                                </td>
                                <td><strong>{{ priceSymbol($transaction->total) }}</strong></td>
                                <td>
                                    @if ($transaction->gateway)
                                        <a href="{{ route('admin.settings.gateways.edit', $transaction->gateway->id) }}"
                                            class="text-dark">
                                            <i class="fas fa-external-link-alt me-2"></i>{{ $transaction->gateway->name }}
                                        @else
                                            <span>--</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->type == 1)
                                        <span class="badge bg-lg-1">{{ admin_lang('Subscribe') }}</span>
                                    @elseif($transaction->type == 2)
                                        <span class="badge bg-lg-2">{{ admin_lang('Renew') }}</span>
                                    @elseif($transaction->type == 3)
                                        <span class="badge bg-lg-9">{{ admin_lang('Upgrade') }}</span>
                                    @elseif($transaction->type == 4)
                                        <span class="badge bg-lg-10">{{ admin_lang('Downgrade') }}</span>
                                    @endif
                                </td>
                                <td>{{ dateFormat($transaction->created_at) }}</td>
                                <td>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                            aria-expanded="true">
                                            <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.transactions.edit', $transaction->id) }}"><i
                                                        class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.transactions.destroy', $transaction->id) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="vironeer-able-to-delete dropdown-item text-danger"><i
                                                            class="far fa-trash-alt me-2"></i>{{ admin_lang('Delete') }}</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                <table class="datatable50 table w-100">
                    <thead>
                        <tr>
                            <th class="tb-w-2x">{{ admin_lang('#ID') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('User') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Plan') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Total') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Gateway') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Type') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('Created at') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($canceledTransactions as $transaction)
                            <tr>
                                <td><a
                                        href="{{ route('admin.transactions.edit', $transaction->id) }}">{{ $transaction->id }}</a>
                                </td>
                                <td><a href="{{ route('admin.users.edit', $transaction->user->id) }}"
                                        class="text-dark"><i class="fa fa-user me-2"></i>
                                        {{ $transaction->user->name }}
                                    </a>
                                </td>
                                <td><a href="{{ route('admin.plans.edit', $transaction->plan->id) }}"><i
                                            class="far fa-gem me-2"></i>
                                        {{ $transaction->plan->name }}
                                    </a>
                                </td>
                                <td><strong>{{ priceSymbol($transaction->total) }}</strong></td>
                                <td>
                                    @if ($transaction->gateway)
                                        <a href="{{ route('admin.settings.gateways.edit', $transaction->gateway->id) }}"
                                            class="text-dark">
                                            <i class="fas fa-external-link-alt me-2"></i>{{ $transaction->gateway->name }}
                                        @else
                                            <span>--</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->type == 1)
                                        <span class="badge bg-lg-1">{{ admin_lang('Subscribe') }}</span>
                                    @elseif($transaction->type == 2)
                                        <span class="badge bg-lg-2">{{ admin_lang('Renew') }}</span>
                                    @elseif($transaction->type == 3)
                                        <span class="badge bg-lg-9">{{ admin_lang('Upgrade') }}</span>
                                    @elseif($transaction->type == 4)
                                        <span class="badge bg-lg-10">{{ admin_lang('Downgrade') }}</span>
                                    @endif
                                </td>
                                <td>{{ dateFormat($transaction->created_at) }}</td>
                                <td>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                            aria-expanded="true">
                                            <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.transactions.edit', $transaction->id) }}"><i
                                                        class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.transactions.destroy', $transaction->id) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="vironeer-able-to-delete dropdown-item text-danger"><i
                                                            class="far fa-trash-alt me-2"></i>{{ admin_lang('Delete') }}</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
