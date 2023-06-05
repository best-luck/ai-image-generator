@extends('frontend.user.layouts.app')
@section('title', lang('Checkout', 'checkout'))
@section('content')
    <div class="checkout">
        @if ($transaction->type == 4)
            <div class="alert alert-primary">
                <h5 class="alert-heading"><i
                        class="fa-regular fa-circle-question me-2"></i>{{ lang('Important Notice !', 'checkout') }}</h5>
                <p class="mb-0"> {{ lang('downgrading notice', 'checkout') }}
                </p>
            </div>
        @elseif($transaction->type == 3)
            <div class="alert alert-primary">
                <h5 class="alert-heading mb-3"><i
                        class="fa-regular fa-circle-question me-2"></i>{{ lang('Important Notice !', 'checkout') }}</h5>
                <p class="mb-0"> {{ lang('upgrading notice', 'checkout') }}
                </p>
            </div>
        @endif
        <div class="row g-3">
            <div class="col-12 col-lg-7 col-xl-8 order-2 order-lg-1">
                <form id="checkoutForm" action="{{ route('checkout.proccess', $transaction->checkout_id) }}" method="POST">
                    @csrf
                    <div class="card-v mb-3">
                        <h6 class="fs-6 text-uppercase mb-4">{{ lang('Payment Methods', 'checkout') }}</h6>
                        @if ($transaction->total != 0)
                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                @forelse ($paymentGateways as $paymentGateway)
                                    <div class="col">
                                        <div class="payment-method">
                                            <div class="payment-img">
                                                <img src="{{ asset($paymentGateway->logo) }}"
                                                    alt="{{ $paymentGateway->name }}">
                                            </div>
                                            <span class="payment-title">{{ $paymentGateway->name }}</span>
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="{{ $paymentGateway->alias }}" value="{{ $paymentGateway->id }}"
                                                {{ $loop->first ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $paymentGateway->alias }}"></label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-lg-12">
                                        <div class="alert alert-info mb-0">
                                            {{ lang('No payment methods available right now please try again later.', 'checkout') }}
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                {{ lang('No payment method needed.', 'checkout') }}
                            </div>
                        @endif
                    </div>
                    <div class="card-v {{Auth::user()?'':'d-none'}}">
                        <h6 class="fs-6 text-uppercase mb-4">{{ lang('Billing address', 'checkout') }}</h6>
                        <div class="row row-cols-1 row-cols-sm-2 g-3 mb-3">
                            <div class="col">
                                <label class="form-label">{{ lang('First Name', 'forms') }} : </label>
                                <input type="firstname" class="form-control form-control-md"
                                    placeholder="{{ lang('First Name', 'forms') }}" value="{{ $user->firstname??'' }}"
                                    readonly>
                            </div>
                            <div class="col">
                                <label class="form-label">{{ lang('Last Name', 'forms') }} : </label>
                                <input type="lastname" class="form-control form-control-md"
                                    placeholder="{{ lang('Last Name', 'forms') }}" value="{{ $user->lastname??'' }}" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ lang('Address line 1', 'forms') }} : <span
                                    class="required">*</span></label>
                            <input type="text" name="address_1" class="form-control form-control-md"
                                value="{{ @$user->address->address_1??'' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ lang('Address line 2', 'forms') }} :</label>
                            <input type="text" name="address_2" class="form-control form-control-md"
                                placeholder="{{ lang('Apartment, suite, etc. (optional)', 'checkout') }}"
                                value="{{ @$user->address->address_2??'' }}">
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ lang('City', 'forms') }} : <span
                                            class="required">*</span></label>
                                    <input type="text" name="city" class="form-control form-control-md"
                                        value="{{ @$user->address->city??'' }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ lang('State', 'forms') }} : <span
                                            class="required">*</span></label>
                                    <input type="text" name="state" class="form-control form-control-md"
                                        value="{{ @$user->address->state??'' }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ lang('Postal code', 'forms') }} : <span
                                            class="required">*</span></label>
                                    <input type="text" name="zip" class="form-control form-control-md"
                                        value="{{ @$user->address->zip??'' }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">{{ lang('Country', 'forms') }} : <span
                                    class="required">*</span></label>
                            <select name="country" class="form-select form-select-md">
                                @foreach (countries() as $country)
                                    <option value="{{ $country->id }}"
                                        {{ $user && $country->name == @$user->address->country ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
                <div class="card-v mt-3 d-block d-lg-none">
                    <div class="protect">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <span
                                class="h6 text-uppercase mb-2 d-block">{{ lang('SSL Secure Payment', 'checkout') }}</span>
                            <p class="text-muted mb-0">
                                {{ lang('Your information is protected by 256-bit SSL encryption', 'checkout') }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-3 d-flex d-lg-none">
                    @if ($transaction->total != 0)
                        <button form="checkoutForm"
                            class="btn btn-primary btn-lg w-100">{{ lang('Pay Now', 'checkout') }}</button>
                    @else
                        <button form="checkoutForm"
                            class="btn btn-primary btn-lg w-100">{{ lang('Continue', 'checkout') }}</button>
                    @endif
                </div>
            </div>
            <div class="col-12 col-lg-5 col-xl-4 order-1 order-lg-2">
                <div class="card-v mb-3">
                    <h6 class="fs-6 text-uppercase mb-4">{{ lang('Order Summary', 'checkout') }}</h6>
                    <div class="plan-payment">
                        <div class="plan-payment-body">
                            @if ($transaction->plan)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-dark">{{ $transaction->plan->name . ' ' . lang('Plan', 'checkout') }}
                                        ({{ formatInterval($transaction->plan->interval) }})</span>
                                    <span
                                        class="h6 mb-0">{{ priceSymbol($transaction->details_before_discount->price) }}</span>
                                </div>
                                @if ($transaction->details_before_discount->tax != 0)
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-dark">{{ lang('Tax', 'checkout') }}</span>
                                        <span
                                            class="h6 mb-0">+{{ priceSymbol($transaction->details_before_discount->tax) }}</span>
                                    </div>
                                @endif
                                @if ($transaction->coupon)
                                    <div class="total d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-dark">{{ lang('Subtotal', 'checkout') }}</span>
                                        <span
                                            class="h6 mb-0">{{ priceSymbol($transaction->details_before_discount->total) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-success">{{ lang('Discount', 'checkout') }}
                                            ({{ $transaction->coupon->percentage }}%)</span>
                                        <span
                                            class="h6 mb-0">-{{ priceSymbol($transaction->details_before_discount->total - $transaction->total) }}</span>
                                    </div>
                                    <div class="total d-flex justify-content-between align-items-center">
                                        <span class="text-dark h5">{{ lang('Total', 'checkout') }}</span>
                                        <span class="h6 mb-0 h5">{{ priceSymbol($transaction->total) }}</span>
                                    </div>
                                @else
                                    <div class="total d-flex justify-content-between align-items-center h6 mt-3 text-primary">
                                        <span class="mb-0 h5">{{ lang('Total', 'checkout') }}</span>
                                        <span class="mb-0 h5">{{ priceSymbol($transaction->total) }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-dark">Download Image</span>
                                    <span
                                        class="h6 mb-0">{{ priceSymbol($transaction->price) }}</span>
                                </div>
                                @if ($transaction->tax != 0)
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-dark">{{ lang('Tax', 'checkout') }}</span>
                                        <span
                                            class="h6 mb-0">+{{ priceSymbol($transaction->tax) }}</span>
                                    </div>
                                @endif
                                <div class="total d-flex justify-content-between align-items-center h6 mt-3 text-primary">
                                    <span class="mb-0 h5">{{ lang('Total', 'checkout') }}</span>
                                    <span class="mb-0 h5">{{ priceSymbol($transaction->total) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if ($transaction->total != 0)
                        <div class="alert alert-warning mb-0 mt-4">
                            <span>{{ lang('Payment gateways may charge extra fees', 'checkout') }}</span>
                        </div>
                    @endif
                </div>
                @if (!($transaction->plan && $transaction->plan->isFree()))
                    <div class="card-v mb-3">
                        <h6 class="fs-6 text-uppercase mb-3">{{ lang('Coupon Code', 'checkout') }}</h6>
                        @if (!$transaction->coupon)
                            <form action="{{ route('checkout.coupon.apply', $transaction->checkout_id) }}"
                                method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="coupon_code" class="form-control form-control-md"
                                        placeholder="{{ lang('Enter coupon code', 'checkout') }}" max="20"
                                        value="{{ old('coupon_code') }}" required>
                                    <button
                                        class="btn btn-primary btn-md pe-3 ps-3">{{ lang('Apply', 'checkout') }}</button>
                                </div>
                            </form>
                        @else
                            <div class="d-flex justify-content-between align-items-center alert alert-primary mb-0 py-2">
                                <span>
                                    <i class="fa-solid fa-ticket me-2"></i>{{ $transaction->coupon->code }}
                                </span>
                                <form action="{{ route('checkout.coupon.remove', $transaction->checkout_id) }}"
                                    method="POST">
                                    @csrf
                                    <button class="btn btn-link p-0 m-0 text-danger action-confirm">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="card-v d-none d-lg-block mb-3">
                    <div class="protect">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <span
                                class="h6 text-uppercase mb-2 d-block">{{ lang('SSL Secure Payment', 'checkout') }}</span>
                            <p class="text-muted mb-0">
                                {{ lang('Your information is protected by 256-bit SSL encryption', 'checkout') }}</p>
                        </div>
                    </div>
                </div>
                <div class="d-none d-lg-flex">
                    @if ($transaction->total != 0)
                        <button form="checkoutForm"
                            class="btn btn-primary btn-lg w-100">{{ lang('Pay Now', 'checkout') }}</button>
                    @else
                        <button form="checkoutForm"
                            class="btn btn-primary btn-lg w-100">{{ lang('Continue', 'checkout') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
