@extends('frontend.layouts.auth')
@section('title', lang('Complete registration', 'auth'))
@section('content')
    <div class="sign-box sign-box-wide">
        <h4>{{ lang('Complete registration', 'auth') }}</h4>
        <p class="text-muted fw-light mb-4">
            {{ lang('We need a little more information to complete your registration.', 'auth') }}</p>
        <form action="{{ route('complete.registration', $token) }}" method="POST">
            @csrf
            @csrf
            <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
                <div class="col">
                    <label class="form-label">{{ lang('First Name', 'forms') }} : <span class="required">*</span></label>
                    <input type="firstname" name="firstname" class="form-control form-control-md"
                        value="{{ $data['firstname'] ?? old('firstname') }}" placeholder="{{ lang('First Name', 'forms') }}"
                        maxlength="50" required>
                </div>
                <div class="col">
                    <label class="form-label">{{ lang('Last Name', 'forms') }} : <span class="required">*</span></label>
                    <input id="lastname" type="lastname" name="lastname" class="form-control form-control-md"
                        value="{{ $data['lastname'] ?? old('lastname') }}" placeholder="{{ lang('Last Name', 'forms') }}"
                        maxlength="50" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ lang('Username', 'forms') }} : <span class="required">*</span></label>
                <input type="username" name="username" class="form-control form-control-md" value="{{ old('username') }}"
                    placeholder="{{ lang('Username', 'forms') }}" minlength="6" maxlength="50" required>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ lang('Email address', 'forms') }} : <span class="required">*</span></label>
                <input type="email" name="email" class="form-control form-control-md"
                    value="{{ $data['email'] ?? old('email') }}" placeholder="{{ lang('Email address', 'forms') }}"
                    required>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ lang('Country', 'forms') }} : <span class="required">*</span></label>
                <select id="country" name="country" class="form-select form-select-md" required>
                    @foreach (countries() as $country)
                        <option data-code="{{ $country->code }}" data-id="{{ $country->id }}"
                            value="{{ $country->id }}" {{ $country->id == old('country') ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ lang('Phone Number', 'forms') }} : <span class="required">*</span></label>
                <div class="form-number">
                    <select id="mobile_code" name="mobile_code" class="form-select form-select-md flex-shrink-0 w-auto">
                        @foreach (countries() as $country)
                            <option data-code="{{ $country->code }}" data-id="{{ $country->id }}"
                                value="{{ $country->id }}" {{ $country->id == old('mobile_code') ? 'selected' : '' }}>
                                {{ $country->code }} ({{ $country->phone }})
                            </option>
                        @endforeach
                    </select>
                    <input id="mobile" type="tel" name="mobile" class="form-control form-control-md"
                        value="{{ old('mobile') }}" placeholder="{{ lang('Phone Number', 'forms') }}" required>
                </div>
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
            @if ($settings->general->terms_of_service_link)
                <div class="mb-3">
                    <div class="form-check">
                        <input id="terms" name="terms" class="form-check-input" type="checkbox"
                            {{ old('terms') ? 'checked' : '' }} required>
                        <label class="form-check-label">
                            {{ lang('I agree to the', 'auth') }} <a href="{{ $settings->general->terms_of_service_link }}"
                                class="link link-primary">{{ lang('terms of service', 'auth') }}</a>
                        </label>
                    </div>
                </div>
            @endif
            {!! display_captcha() !!}
            <button class="btn btn-primary btn-md w-100">{{ lang('Sign Up', 'auth') }}</button>
        </form>
    </div>
@endsection
