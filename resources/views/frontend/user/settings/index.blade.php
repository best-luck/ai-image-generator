@extends('frontend.user.layouts.app')
@section('title', lang('Settings', 'account'))
@section('container', 'dash-container-small')
@section('content')
    <div class="settings">
        <div class="row g-3">
            @include('frontend.user.includes.settings-sidebar')
            <div class="col-lg-8 col-xxl-9">
                <div class="card-v p-0">
                    <div class="settings-box">
                        <div class="settings-box-header border-bottom px-4 py-3">
                            <h5 class="mb-0">{{ lang('Account details', 'account') }}</h5>
                        </div>
                        <div class="settings-box-body p-4">
                            <form id="deatilsForm" action="{{ route('user.settings.details.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input id="change_avatar" type="file" name="avatar"
                                    accept="image/jpg, image/jpeg, image/png" hidden />
                                <div class="row row-cols-1 row-cols-sm-2 g-3 mb-3">
                                    <div class="col">
                                        <label class="form-label">{{ lang('First Name', 'forms') }} : <span
                                                class="required">*</span></label>
                                        <input type="firstname" name="firstname" class="form-control form-control-md"
                                            placeholder="{{ lang('First Name', 'forms') }}" maxlength="50"
                                            value="{{ $user->firstname }}" required>
                                    </div>
                                    <div class="col">
                                        <label class="form-label">{{ lang('Last Name', 'forms') }} : <span
                                                class="required">*</span></label>
                                        <input type="lastname" name="lastname" class="form-control form-control-md"
                                            placeholder="{{ lang('Last Name', 'forms') }}" maxlength="50"
                                            value="{{ $user->lastname }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ lang('Email address', 'forms') }} : <span
                                            class="required">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-md"
                                        placeholder="{{ lang('Email address', 'forms') }}" value="{{ $user->email }}"
                                        required>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ lang('Username', 'forms') }} : </label>
                                            <input class="form-control form-control-md"
                                                placeholder="{{ lang('Username', 'forms') }}"
                                                value="{{ $user->username }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ lang('Phone Number', 'forms') }} : </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-md"
                                                    placeholder="{{ lang('Phone Number', 'forms') }}"
                                                    value="{{ $user->mobile }}" readonly>
                                                <button class="btn btn-primary btn-md pe-3 ps-3" data-bs-toggle="modal"
                                                    data-bs-target="#mobileModal" type="button"><i
                                                        class="fas fa-sync-alt"></i><span
                                                        class="ms-2 d-none d-lg-inline">{{ lang('Change', 'account') }}</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ lang('Address line 1', 'forms') }} : <span
                                            class="required">*</span></label>
                                    <input type="text" name="address_1" class="form-control form-control-md"
                                        value="{{ @$user->address->address_1 }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ lang('Address line 2', 'forms') }} :</label>
                                    <input type="text" name="address_2" class="form-control form-control-md"
                                        placeholder="{{ lang('Apartment, suite, etc. (optional)', 'account') }}"
                                        value="{{ @$user->address->address_2 }}">
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">{{ lang('City', 'forms') }} : <span
                                                    class="required">*</span></label>
                                            <input type="text" name="city" class="form-control form-control-md"
                                                value="{{ @$user->address->city }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">{{ lang('State', 'forms') }} : <span
                                                    class="required">*</span></label>
                                            <input type="text" name="state" class="form-control form-control-md"
                                                value="{{ @$user->address->state }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">{{ lang('Postal code', 'forms') }} : <span
                                                    class="required">*</span></label>
                                            <input type="text" name="zip" class="form-control form-control-md"
                                                value="{{ @$user->address->zip }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">{{ lang('Country', 'forms') }} : <span
                                            class="required">*</span></label>
                                    <select name="country" class="form-select form-select-md" required>
                                        @foreach (countries() as $country)
                                            <option value="{{ $country->id }}"
                                                {{ $country->name == @$user->address->country ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-primary btn-md">{{ lang('Save Changes', 'account') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mobileModal" tabindex="-1" aria-labelledby="mobileModalLabel"
        data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('user.settings.details.mobile.update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ lang('Phone Number', 'forms') }} : <span
                                    class="required">*</span></label>
                            <div class="form-number">
                                <select id="mobile_code" name="mobile_code"
                                    class="form-select flex-shrink-0 w-auto form-select-md">
                                    @foreach (countries() as $country)
                                        <option value="{{ $country->id }}"
                                            {{ $country->name == @$user->address->country ? 'selected' : '' }}>
                                            {{ $country->code }}
                                            ({{ $country->phone }})
                                        </option>
                                    @endforeach
                                </select>
                                <input id="mobile" type="tel" name="mobile" class="form-control form-control-md"
                                    placeholder="{{ lang('Phone Number', 'forms') }}" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit"
                                class="btn btn-primary btn-md w-100 me-2">{{ lang('Save', 'account') }}</button>
                            <button type="button" class="btn btn-light btn-md w-100 ms-2"
                                data-bs-dismiss="modal">{{ lang('Close') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
