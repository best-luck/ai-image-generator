@extends('frontend.layouts.auth')
@section('title', lang('Reset Password', 'auth'))
@section('content')
    <div class="sign-box">
        <h4>{{ lang('Reset Password', 'auth') }}</h4>
        <p class="text-muted fw-light mb-4">
            {{ lang('Enter a new password to continue.', 'auth') }}</p>
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label class="form-label">{{ lang('Email address', 'forms') }} : <span class="required">*</span></label>
                <input type="email" name="email" class="form-control form-control-md" value="{{ $email }}"
                    placeholder="{{ lang('Email address', 'forms') }}" readonly />
            </div>
            <div class="mb-3">
                <label class="form-label">{{ lang('Password', 'forms') }} : <span class="required">*</span>
                </label>
                <input type="password" name="password" class="form-control form-control-md"
                    placeholder="{{ lang('Password', 'forms') }}" minlength="8" required>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ lang('Confirm password', 'forms') }} : <span class="required">*</span>
                </label>
                <input type="password" name="password_confirmation" class="form-control form-control-md"
                    placeholder="{{ lang('Confirm password', 'forms') }}" minlength="8" required>
            </div>
            {!! display_captcha() !!}
            <button class="btn btn-primary btn-md w-100">{{ lang('Reset', 'auth') }}</button>
        </form>
    </div>
@endsection
