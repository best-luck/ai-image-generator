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
                            <h5 class="mb-0">{{ lang('Change Password', 'account') }}</h5>
                        </div>
                        <div class="settings-box-body p-4">
                            <form id="deatilsForm" action="{{ route('user.settings.password.update') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">{{ lang('Password', 'forms') }} : <span
                                            class="required">*</span></label>
                                    <input type="password" class="form-control form-control-md" name="current-password"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ lang('New Password', 'forms') }} : <span
                                            class="required">*</span></label>
                                    <input type="password" class="form-control form-control-md" name="new-password"
                                        required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">{{ lang('Confirm New Password', 'forms') }} : <span
                                            class="required">*</span></label>
                                    <input type="password" class="form-control form-control-md"
                                        name="new-password_confirmation" required>
                                </div>
                                <button class="btn btn-primary btn-md">{{ lang('Save Changes', 'account') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
