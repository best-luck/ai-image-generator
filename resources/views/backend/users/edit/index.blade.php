@extends('backend.layouts.form')
@section('section', admin_lang('Users'))
@section('title', $user->name . ' | Details')
@section('back', route('admin.users.index'))
@section('content')
    <div class="row">
        <div class="col-lg-3">
            @include('backend.includes.userlist')
        </div>
        <div class="col-lg-9">
            <form id="vironeer-submited-form" action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card custom-card mb-3">
                    <div class="card-header">{{ admin_lang('Actions') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 my-1">
                                <label class="form-label">{{ admin_lang('Account status') }} : </label>
                                <input type="checkbox" name="status" data-toggle="toggle"
                                    data-on="{{ admin_lang('Active') }}" data-off="{{ admin_lang('Banned') }}"
                                    {{ $user->status ? 'checked' : '' }}>
                            </div>
                            <div class="col-lg-4 my-1">
                                <label class="form-label">{{ admin_lang('Email status') }} : </label>
                                <input type="checkbox" name="email_status" data-toggle="toggle"
                                    data-on="{{ admin_lang('Verified') }}" data-off="{{ admin_lang('Unverified') }}"
                                    {{ !is_null($user->email_verified_at) ? 'checked' : '' }}>
                            </div>
                            <div class="col-lg-4 my-1">
                                <label class="form-label">{{ admin_lang('Two-Factor Authentication') }} : </label>
                                <input id="2faCheckbox" type="checkbox" name="google2fa_status" data-toggle="toggle"
                                    data-on="{{ admin_lang('Active') }}" data-off="{{ admin_lang('Disabled') }}"
                                    {{ $user->google2fa_status ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-card">
                    <div class="card-header">{{ admin_lang('Account details') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Firstname') }} :<span
                                            class="red">*</span></label>
                                    <input type="firstname" name="firstname" class="form-control form-control-lg"
                                        value="{{ $user->firstname }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Lastname') }} :<span
                                            class="red">*</span></label>
                                    <input type="lastname" name="lastname" class="form-control form-control-lg"
                                        value="{{ $user->lastname }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Username') }} :<span class="red">*</span></label>
                            <input type="username" name="username" class="form-control form-control-lg"
                                value="{{ $user->username }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('E-mail Address') }} :<span
                                    class="red">*</span></label>
                            <div class="input-group mb-3">
                                <input type="email" name="email" class="form-control form-control-lg"
                                    value="{{ $user->email }}" required>
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                    data-bs-target="#sendMailModal"><i
                                        class="far fa-paper-plane me-2"></i>{{ admin_lang('Send Email') }}</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Phone number') }} :<span
                                    class="red">*</span></label>
                            <input type="mobile" name="mobile" class="form-control form-control-lg"
                                value="{{ $user->mobile }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Address line 1') }} :</label>
                            <input type="text" name="address_1" class="form-control form-control-lg"
                                value="{{ @$user->address->address_1 }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Address line 2') }} :</label>
                            <input type="text" name="address_2" class="form-control form-control-lg"
                                placeholder="{{ admin_lang('Apartment, suite, etc. (optional)') }}"
                                value="{{ @$user->address->address_2 }}">
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('City') }} :</label>
                                    <input type="text" name="city" class="form-control form-control-lg"
                                        value="{{ @$user->address->city }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('State') }} :</label>
                                    <input type="text" name="state" class="form-control form-control-lg"
                                        value="{{ @$user->address->state }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Postal code') }} :</label>
                                    <input type="text" name="zip" class="form-control form-control-lg"
                                        value="{{ @$user->address->zip }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">{{ admin_lang('Country') }} :</label>
                            <select name="country" class="form-select form-control-lg">
                                <option value="" selected disabled>{{ admin_lang('Choose') }}</option>
                                @foreach (countries() as $country)
                                    <option value="{{ $country->id }}" @if ($country->name == @$user->address->country) selected @endif>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="sendMailModal" tabindex="-1" aria-labelledby="sendMailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendMailModalLabel">{{ admin_lang('Send Mail to ') }}{{ $user->email }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.sendmail', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Subject') }} : <span
                                            class="red">*</span></label>
                                    <input type="subject" name="subject" class="form-control form-control-lg" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Reply to') }} : <span
                                            class="red">*</span></label>
                                    <input type="email" name="reply_to" class="form-control form-control-lg"
                                        value="{{ adminAuthInfo()->email }}" required>
                                </div>
                            </div>
                        </div>
                        <textarea name="message" rows="10" class="ckeditor"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-lg">{{ admin_lang('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/ckeditor/plugins/uploadAdapterPlugin.js') }}"></script>
    @endpush
@endsection
