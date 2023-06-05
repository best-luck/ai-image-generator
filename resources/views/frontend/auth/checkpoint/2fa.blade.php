@extends('frontend.layouts.auth')
@section('title', lang('2Fa Verification', 'auth'))
@section('content')
    <div class="sign-box">
        <h4>{{ lang('2Fa Verification', 'auth') }}</h4>
        <p class="text-muted fw-light mb-4">{{ lang('Please enter the OTP code to continue', 'auth') }}</p>
        <form action="{{ route('2fa.verify') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="text" name="otp_code" class="form-control form-control-md input-numeric" placeholder="••• •••"
                    maxlength="6" required>
            </div>
            <button class="btn btn-primary btn-lg w-100">{{ lang('Continue', 'auth') }}</button>
        </form>
    </div>
@endsection
