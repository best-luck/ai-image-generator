@extends('backend.layouts.grid')
@section('title', admin_lang('Subscriptions'))
@section('add_modal', admin_lang('Add New'))
@section('content')
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-6 col-xxl">
            <div class="counter-card v3 c-light-green">
                <div class="counter-card-icon">
                    <i class="far fa-check-circle"></i>
                </div>
                <div class="counter-card-info">
                    <p class="counter-card-number">{{ $activeSubscriptions->count() }}</p>
                    <p class="counter-card-title">{{ admin_lang('Active Subscriptions') }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl">
            <div class="counter-card v3 c-light-red">
                <div class="counter-card-icon">
                    <i class="far fa-clock"></i>
                </div>
                <div class="counter-card-info">
                    <p class="counter-card-number">{{ $expiredSubscriptions->count() }}</p>
                    <p class="counter-card-title">{{ admin_lang('Expired Subscriptions') }}</p>
                </div>
            </div>
        </div>
        @if ($canceledSubscriptions->count() > 0)
            <div class="col-12 col-lg-6 col-xxl">
                <div class="counter-card v3 c-red">
                    <div class="counter-card-icon">
                        <i class="far fa-times-circle"></i>
                    </div>
                    <div class="counter-card-info">
                        <p class="counter-card-number">{{ $canceledSubscriptions->count() }}</p>
                        <p class="counter-card-title">{{ admin_lang('Canceled Subscriptions') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="card custom-card custom-tabs mb-3">
        <div class="card-body">
            <ul class="nav nav-pills" role="tablist">
                <li role="presentation">
                    <button class="nav-link active me-2" id="active-tab" data-bs-toggle="tab" data-bs-target="#active"
                        type="button" role="tab" aria-controls="active" aria-selected="true"><i
                            class="far fa-check-circle me-2"></i>{{ admin_lang('Active') }}
                        ({{ $activeSubscriptions->count() }})
                    </button>
                </li>
                <li role="presentation">
                    <button class="nav-link me-2" id="expired-tab" data-bs-toggle="tab" data-bs-target="#expired"
                        type="button" role="tab" aria-controls="expired" aria-selected="false"><i
                            class="far fa-clock me-2"></i>{{ admin_lang('Expired') }}
                        ({{ $expiredSubscriptions->count() }})
                    </button>
                </li>
                @if ($canceledSubscriptions->count() > 0)
                    <li role="presentation">
                        <button class="nav-link" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled"
                            type="button" role="tab" aria-controls="canceled" aria-selected="false"><i
                                class="far fa-times-circle me-2"></i>{{ admin_lang('Canceled') }}
                            ({{ $canceledSubscriptions->count() }})
                        </button>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card custom-card">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                <table class="datatable50 table w-100">
                    <thead>
                        <tr>
                            <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                            <th class="tb-w-20x">{{ admin_lang('User details') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('Plan') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('Subscribe at') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('Expiring at') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activeSubscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->id }}</td>
                                <td>
                                    <div class="vironeer-user-box">
                                        <a class="vironeer-user-avatar"
                                            href="{{ route('admin.users.edit', $subscription->user->id) }}">
                                            <img src="{{ asset($subscription->user->avatar) }}">
                                        </a>
                                        <div>
                                            <a class="text-reset"
                                                href="{{ route('admin.users.edit', $subscription->user->id) }}">
                                                {{ $subscription->user->name }}</a>
                                            <p class="text-muted mb-0">{{ $subscription->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><a href="{{ route('admin.plans.edit', $subscription->plan->id) }}"><i
                                            class="far fa-gem me-2"></i>
                                        {{ $subscription->plan->name }}
                                    </a>
                                </td>
                                <td>{{ dateFormat($subscription->created_at) }}</td>
                                <td>
                                    <span class="{{ $subscription->isExpired() ? 'text-danger' : 'text-dark' }}">
                                        {{ dateFormat($subscription->expiry_at) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($subscription->isExpired())
                                        <span class="badge bg-danger">{{ admin_lang('Expired') }}</span>
                                    @elseif($subscription->isActive())
                                        <span class="badge bg-success">{{ admin_lang('Active') }}</span>
                                    @else
                                        <span class="badge bg-lg-4">{{ admin_lang('Canceled') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                            aria-expanded="true">
                                            <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-sm-end dropdown-menu-lg"
                                            data-popper-placement="bottom-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.subscriptions.edit', $subscription->id) }}"><i
                                                        class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.users.edit', $subscription->user->id) }}"><i
                                                        class="fa fa-user me-2"></i>{{ admin_lang('User details') }}</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li>
                                                <form
                                                    action="{{ route('admin.subscriptions.destroy', $subscription->id) }}"
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
            <div class="tab-pane fade" id="expired" role="tabpanel" aria-labelledby="expired-tab">
                <table class="datatable50 table w-100">
                    <thead>
                        <tr>
                            <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                            <th class="tb-w-20x">{{ admin_lang('User details') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('Plan') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('Subscribe at') }}</th>
                            <th class="tb-w-7x">{{ admin_lang('Expiring at') }}</th>
                            <th class="tb-w-3x">{{ admin_lang('Status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expiredSubscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->id }}</td>
                                <td>
                                    <div class="vironeer-user-box">
                                        <a class="vironeer-user-avatar"
                                            href="{{ route('admin.subscriptions.edit', $subscription->id) }}">
                                            <img src="{{ asset($subscription->user->avatar) }}">
                                        </a>
                                        <div>
                                            <a class="text-reset"
                                                href="{{ route('admin.users.edit', $subscription->user->id) }}">
                                                {{ $subscription->user->name }}</a>
                                            <p class="text-muted mb-0">{{ $subscription->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><a href="{{ route('admin.plans.edit', $subscription->plan->id) }}"><i
                                            class="far fa-gem me-2"></i>
                                        {{ $subscription->plan->name }}
                                    </a>
                                </td>
                                <td>{{ dateFormat($subscription->created_at) }}</td>
                                <td>
                                    <span class="{{ $subscription->isExpired() ? 'text-danger' : 'text-dark' }}">
                                        {{ dateFormat($subscription->expiry_at) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($subscription->isExpired())
                                        <span class="badge bg-danger">{{ admin_lang('Expired') }}</span>
                                    @elseif($subscription->isActive())
                                        <span class="badge bg-success">{{ admin_lang('Active') }}</span>
                                    @else
                                        <span class="badge bg-lg-4">{{ admin_lang('Canceled') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                            aria-expanded="true">
                                            <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-sm-end dropdown-menu-lg"
                                            data-popper-placement="bottom-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.subscriptions.edit', $subscription->id) }}"><i
                                                        class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.users.edit', $subscription->user->id) }}"><i
                                                        class="fa fa-user me-2"></i>{{ admin_lang('User details') }}</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li>
                                                <form
                                                    action="{{ route('admin.subscriptions.destroy', $subscription->id) }}"
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
            @if ($canceledSubscriptions->count() > 0)
                <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                    <table class="datatable50 table w-100">
                        <thead>
                            <tr>
                                <th class="tb-w-2x">{{ admin_lang('#') }}</th>
                                <th class="tb-w-20x">{{ admin_lang('User details') }}</th>
                                <th class="tb-w-7x">{{ admin_lang('Plan') }}</th>
                                <th class="tb-w-7x">{{ admin_lang('Subscribe at') }}</th>
                                <th class="tb-w-7x">{{ admin_lang('Expiring at') }}</th>
                                <th class="tb-w-3x">{{ admin_lang('Status') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($canceledSubscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->id }}</td>
                                    <td>
                                        <div class="vironeer-user-box">
                                            <a class="vironeer-user-avatar"
                                                href="{{ route('admin.users.edit', $subscription->user->id) }}">
                                                <img src="{{ asset($subscription->user->avatar) }}">
                                            </a>
                                            <div>
                                                <a class="text-reset"
                                                    href="{{ route('admin.users.edit', $subscription->user->id) }}">
                                                    {{ $subscription->user->name }}</a>
                                                <p class="text-muted mb-0">{{ $subscription->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a href="{{ route('admin.plans.edit', $subscription->plan->id) }}"
                                            style="color: {{ $subscription->plan->color }}"><i
                                                class="far fa-gem me-2"></i>
                                            {{ $subscription->plan->name }}
                                        </a>
                                    </td>
                                    <td>{{ dateFormat($subscription->created_at) }}</td>
                                    <td>
                                        <span class="{{ $subscription->isExpired() ? 'text-danger' : 'text-dark' }}">
                                            {{ dateFormat($subscription->expiry_at) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($subscription->isExpired())
                                            <span class="badge bg-danger">{{ admin_lang('Expired') }}</span>
                                        @elseif($subscription->isActive())
                                            <span class="badge bg-success">{{ admin_lang('Active') }}</span>
                                        @else
                                            <span class="badge bg-lg-4">{{ admin_lang('Canceled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                aria-expanded="true">
                                                <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-sm-end dropdown-menu-lg"
                                                data-popper-placement="bottom-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.subscriptions.edit', $subscription->id) }}"><i
                                                            class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.users.edit', $subscription->user->id) }}"><i
                                                            class="fa fa-user me-2"></i>{{ admin_lang('User details') }}</a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('admin.subscriptions.destroy', $subscription->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button
                                                            class="vironeer-able-to-delete dropdown-item text-danger"><i
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
            @endif
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h6 class="modal-title" id="addModalLabel">{{ admin_lang('New Subscription') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('User') }} : <span class="red">*</span></label>
                            <select id="vironeer-select-user" name="user" class="form-select select2Modal" required>
                                <option></option>
                                @foreach ($users as $user)
                                    @if (!$user->isSubscribed())
                                        <option value="{{ $user->id }}"
                                            {{ old('user') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">{{ admin_lang('Plan') }} : <span class="red">*</span></label>
                            <select name="plan" class="form-select" required>
                                <option value="" selected disabled>{{ admin_lang('Choose') }}</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}"
                                        {{ old('plan') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }}
                                        {{ $plan->interval == 1 ? admin_lang('(Monthly)') : admin_lang('(Yearly)') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary">{{ admin_lang('Save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
