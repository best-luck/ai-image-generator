@extends('frontend.layouts.auth')
@section('title', lang('Reset Password', 'auth'))
@section('content')
    <div class="sign-box">
        <h4>{{ lang('Reset Password', 'auth') }}</h4>
        <p class="text-muted fw-light mb-4">
            {{ lang('reset password description', 'auth') }}.</p>
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ lang('Email address', 'forms') }} : <span class="required">*</span></label>
                <input type="email" name="email" class="form-control form-control-md" value="{{ old('email') }}"
                    placeholder="{{ lang('Email address', 'forms') }}" required />
            </div>
            {!! display_captcha() !!}
            <button class="btn btn-primary btn-md w-100">{{ lang('Reset', 'auth') }}</button>
        </form>
    </div>
@endsection
