@extends('backend.layouts.form')
@section('title', admin_lang('Edit coupon') . ' | ' . $coupon->code)
@section('back', route('admin.coupons.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')
        @if ($coupon->isExpiry())
            <div class="alert alert-danger">
                {{ admin_lang('This coupon has been expired') }}
            </div>
        @endif
        <div class="card mb-3 custom-card">
            <div class="card-body">
                <h5 class="card-title">{{ admin_lang('Coupon code') }}</h5>
                <p class="text-secondary">
                    {{ admin_lang('Enter or generate a coupon code, only characters and numbers are allowed in min 3 and max 20 characters') }}
                </p>
                <div class="input-group mb-2">
                    <button class="btn btn-secondary copy-btn" type="button" data-clipboard-target="#coupon-code"><i
                            class="far fa-clone"></i></button>
                    <input id="coupon-code" type="text" name="code" class="form-control form-control-lg"
                        placeholder="{{ admin_lang('Coupon code') }}" maxlength="20" value="{{ $coupon->code }}" readonly>

                    <button class="btn btn-primary" type="button" disabled><i
                            class="fas fa-sync me-2"></i>{{ admin_lang('Generate') }}</button>
                </div>
            </div>
        </div>
        <div class="card mb-3 custom-card">
            <div class="card-body">
                <h5 class="card-title">{{ admin_lang('Discount details') }}</h5>
                <p class="text-secondary">
                    {{ admin_lang('Enter discount percentage in minimum 1% and max 100%, and set limit for each user starting from 1 minimum') }}
                </p>
                <div class="row g-3 mb-2">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Discount percentage') }} : <span
                                class="red">*</span></label>
                        <div class="custom-input-group input-group">
                            <input type="number" class="form-control form-control-lg" min="1" max="100"
                                placeholder="0" value="{{ $coupon->percentage }}" disabled>
                            <span class="input-group-text bg-secondary-gradient pe-4 ps-4 disabled"><i
                                    class="fas fa-percent"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Limit for each user') }} : <span
                                class="red">*</span></label>
                        <input type="number" name="limit" class="form-control form-control-lg" min="1"
                            placeholder="0" value="{{ $coupon->limit }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card custom-card">
            <div class="card-body">
                <h5 class="card-title">{{ admin_lang('Usage details') }}</h5>
                <p class="text-secondary">
                    {{ admin_lang('Set the usage details of the coupon, and set expiry date in minimum 5 minutes') }}
                </p>
                <div class="row g-3 mb-2">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Plan') }} : <span class="red">*</span></label>
                        <select class="form-select form-control-lg" disabled>
                            <option value="0">{{ admin_lang('All plans') }}</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" {{ $coupon->plan_id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }}
                                    ({{ formatInterval($plan->interval) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Action type') }} : <span class="red">*</span></label>
                        <select class="form-select form-control-lg" disabled>
                            <option value="0" {{ $coupon->action_type == 0 ? 'selected' : '' }}>
                                {{ admin_lang('All actions') }}
                            </option>
                            <option value="1" {{ $coupon->action_type == 1 ? 'selected' : '' }}>
                                {{ admin_lang('Subscribing') }}
                            </option>
                            <option value="2" {{ $coupon->action_type == 2 ? 'selected' : '' }}>
                                {{ admin_lang('Renewal') }}
                            </option>
                            <option value="3" {{ $coupon->action_type == 3 ? 'selected' : '' }}>
                                {{ admin_lang('Upgrade') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label">{{ admin_lang('Expiry at') }} : <span class="red">*</span></label>
                        <input type="datetime-local" name="expiry_at" class="form-control form-control-lg"
                            value="{{ \Carbon\Carbon::parse($coupon->expiry_at)->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
