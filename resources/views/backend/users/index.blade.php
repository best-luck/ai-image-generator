@extends('backend.layouts.grid')
@section('title', admin_lang('Users'))
@section('link', route('admin.users.create'))
@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="counter-card v3 c-light-green">
                <div class="counter-card-icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="counter-card-info">
                    <p class="counter-card-number">{{ $activeUsersCount }}</p>
                    <p class="counter-card-title">{{ admin_lang('Active Users') }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="counter-card v3 c-light-red">
                <div class="counter-card-icon">
                    <i class="fa fa-ban"></i>
                </div>
                <div class="counter-card-info">
                    <p class="counter-card-number">{{ $bannedUserscount }}</p>
                    <p class="counter-card-title">{{ admin_lang('Banned Users') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="custom-card card">
        <div class="card-header p-3 border-bottom-small">
            <form action="{{ request()->url() }}" method="GET">
                <div class="input-group vironeer-custom-input-group">
                    <input type="text" name="search" class="form-control"
                        placeholder="{{ admin_lang('Search on users...') }}" value="{{ request()->input('search') ?? '' }}"
                        required>
                    <button class="btn btn-secondary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">{{ admin_lang('Filter') }}</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item"
                                href="{{ request()->url() . '?filter=active' }}">{{ admin_lang('Active') }}</a></li>
                        <li><a class="dropdown-item"
                                href="{{ request()->url() . '?filter=banned' }}">{{ admin_lang('Banned') }}</a></li>
                    </ul>
                </div>
            </form>
        </div>
        <div>
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="vironeer-normal-table table w-100">
                        <thead>
                            <tr>
                                <th class="tb-w-3x">#</th>
                                <th class="tb-w-20x">{{ admin_lang('User details') }}</th>
                                <th class="tb-w-7x">{{ admin_lang('Phone number') }}</th>
                                <th class="tb-w-3x text-center">{{ admin_lang('Subscription') }}</th>
                                <th class="tb-w-3x text-center">{{ admin_lang('Email status') }}</th>
                                <th class="tb-w-3x text-center">{{ admin_lang('Account status') }}</th>
                                <th class="tb-w-3x text-center">{{ admin_lang('Registred date') }}</th>
                                <th class="text-end"><i class="fas fa-sliders-h me-1"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="vironeer-user-box">
                                            <a class="vironeer-user-avatar"
                                                href="{{ route('admin.users.edit', $user->id) }}">
                                                <img src="{{ asset($user->avatar) }}" alt="User" />
                                            </a>
                                            <div>
                                                <a class="text-reset"
                                                    href="{{ route('admin.users.edit', $user->id) }}">{{ $user->name }}</a>
                                                <p class="text-muted mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->mobile }}</td>
                                    <td class="text-center">
                                        @if ($user->isSubscribed())
                                            @if ($user->subscription->isCancelled())
                                                <div class="badge bg-lg-7">{{ admin_lang('Canceled') }}</div>
                                            @elseif ($user->subscription->isExpired())
                                                <div class="badge bg-danger">{{ admin_lang('Expired') }}</div>
                                            @else
                                                <span class="badge bg-lg-1">{{ admin_lang('Subscribed') }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">{{ admin_lang('Unsubscribed') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-girl">{{ admin_lang('Verified') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ admin_lang('Unverified') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->status)
                                            <span class="badge bg-success">{{ admin_lang('Active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ admin_lang('Banned') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ dateFormat($user->created_at) }}</td>
                                    <td>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                aria-expanded="true">
                                                <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-sm-end"
                                                data-popper-placement="bottom-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.users.edit', $user->id) }}"><i
                                                            class="fas fa-edit me-2"></i>{{ admin_lang('Edit Details') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.images.index', 'user=' . $user->id) }}"><i
                                                            class="far fa-images me-2"></i>{{ admin_lang('Images') }}</a>
                                                </li>
                                                @if ($user->isSubscribed())
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.subscriptions.edit', $user->subscription->id) }}"><i
                                                                class="far fa-gem me-2"></i>{{ admin_lang('Subscription') }}</a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
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
            @else
                @include('backend.includes.empty')
            @endif
        </div>
    </div>
    {{ $users->links() }}
@endsection
