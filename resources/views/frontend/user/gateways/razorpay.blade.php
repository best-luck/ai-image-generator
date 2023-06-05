@extends('frontend.user.layouts.app')
@section('title', lang('Payment confirm', 'checkout'))
@section('content')
    <div class="checkout my-5">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card-v mb-4">
                    <h5 class="mb-3">{{ lang('Payment deatils', 'checkout') }}</h5>
                    <table class="table table-bordered table-striped shadow-none mb-3">
                        <tbody>
                            <tr>
                                <td class="p-3"><strong>{{ lang('Plan price', 'checkout') }}</strong></td>
                                <td class="p-3">{{ priceSymbol($trx->details_before_discount->price) }}</td>
                            </tr>
                            <tr>
                                <td class="p-3"><strong>{{ lang('Tax', 'checkout') }}</strong></td>
                                <td class="p-3">{{ priceSymbol($trx->details_before_discount->tax) }}</td>
                            </tr>
                            <tr>
                                <td class="p-3">
                                    <h6 class="mb-0"><strong>{{ lang('Subtotal', 'checkout') }}</strong></h6>
                                </td>
                                <td class="p-3">
                                    <h6 class="mb-0">
                                        <strong>{{ priceSymbol($trx->details_before_discount->total) }}</strong>
                                    </h6>
                                </td>
                            </tr>
                            @if (!is_null($trx->coupon_id))
                                <tr>
                                    <td class="p-3"><strong>{{ lang('Discount', 'checkout') }}</strong>
                                        ({{ $trx->coupon->percentage }}%)</td>
                                    <td class="p-3 text-danger">
                                        -{{ priceSymbol($trx->details_before_discount->total - $trx->details_after_discount->total) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="p-3"><strong>{{ lang('Gateway fees', 'checkout') }}</strong></td>
                                <td class="p-3">
                                    +{{ priceSymbol($trx->fees) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3">
                                    <h5 class="mb-0"><strong>{{ lang('Total', 'checkout') }}</strong></h5>
                                </td>
                                <td class="p-3">
                                    <h5 class="mb-0">
                                        <strong>{{ priceSymbol($trx->total + $trx->fees) }}</strong>
                                    </h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <form action="{{ route('ipn.razorpay') }}" method="POST">
                        @csrf
                        <input type="hidden" name="checkout_id" value="{{ $trx->checkout_id }}">
                        <script src="https://checkout.razorpay.com/v1/checkout.js"
                            @foreach ($details as $key => $value)
                                data-{{ $key }}="{{ $value }}" @endforeach>
                        </script>
                    </form>
                    <a href="{{ route('user.settings.subscription') }}"
                        class="btn btn-outline-primary btn-lg w-100 mt-3">{{ lang('Cancel Payment', 'checkout') }}</a>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            "use strict";
            let razorpayPaymentButton = $('.razorpay-payment-button');
            razorpayPaymentButton.addClass('btn btn-primary btn-lg w-100');
        </script>
    @endpush
@endsection
