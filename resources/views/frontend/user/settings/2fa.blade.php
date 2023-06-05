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
                            <h5 class="mb-0">{{ lang('2FA Authentication', 'account') }}</h5>
                        </div>
                        <div class="settings-box-body p-4">
                            <p class="text-muted">
                                {{ lang('2fa top description', 'account') }}
                            </p>
                            <div class="row my-3">
                                <div class="col-lg-5 m-auto">
                                    @if (!$user->google2fa_status)
                                        <div class="text-center mb-2">
                                            {!! $QR_Image !!}
                                        </div>
                                        <div class="input-group mb-3">
                                            <input id="input-link" type="text" class="form-control form-control-md"
                                                value="{{ $user->google2fa_secret }}" readonly>
                                            <button class="btn btn-light btn-copy" data-clipboard-target="#input-link"><i
                                                    class="far fa-clone"></i></button>
                                        </div>
                                        <a href="#" class="btn btn-primary btn-md w-100" data-bs-toggle="modal"
                                            data-bs-target="#towfactorModal">{{ lang('Enable 2FA Authentication', 'account') }}</a>
                                    @else
                                        <a href="#" class="btn btn-danger btn-md w-100" data-bs-toggle="modal"
                                            data-bs-target="#towfactorDisableModal">{{ lang('Disable 2FA Authentication', 'account') }}</a>
                                    @endif
                                </div>
                            </div>
                            <p class="text-muted mb-2">
                                {{ lang('2fa bottom description', 'account') }}:
                            </p>
                            <li class="mb-1"><a target="_blank"
                                    href="https://apps.apple.com/us/app/google-authenticator/id388497605">{{ lang('Google Authenticator for iOS', 'account') }}</a>
                            </li>
                            <li class="mb-1"><a target="_blank"
                                    href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US">{{ lang('Google Authenticator for Android', 'account') }}</a>
                            </li>
                            <li class="mb-1"><a target="_blank"
                                    href="https://apps.apple.com/us/app/microsoft-authenticator/id983156458">{{ lang('Microsoft Authenticator for iOS', 'account') }}</a>
                            </li>
                            <li><a target="_blank"
                                    href="https://play.google.com/store/apps/details?id=com.azure.authenticator&hl=en_US&gl=US">{{ lang('Microsoft Authenticator for Android', 'account') }}</a>
                            </li>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!$user->google2fa_status)
        <div class="modal fade" id="towfactorModal" tabindex="-1" aria-labelledby="towfactorModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('user.settings.2fa.enable') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ lang('OTP Code', 'forms') }} : <span
                                        class="required">*</span></label>
                                <input type="text" name="otp_code" class="form-control form-control-md input-numeric"
                                    placeholder="••• •••" maxlength="6" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit"
                                    class="btn btn-primary btn-md w-100 me-2">{{ lang('Enable', 'account') }}</button>
                                <button type="button" class="btn btn-light btn-md w-100 ms-2"
                                    data-bs-dismiss="modal">{{ lang('Close') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="modal fade" id="towfactorDisableModal" tabindex="-1" aria-labelledby="towfactorDisableModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('user.settings.2fa.disable') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ lang('OTP Code', 'forms') }} : <span
                                        class="required">*</span></label>
                                <input type="text" name="otp_code" class="form-control form-control-md input-numeric"
                                    placeholder="••• •••" maxlength="6" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit"
                                    class="btn btn-danger btn-md w-100 me-2">{{ lang('Disable', 'account') }}</button>
                                <button type="button" class="btn btn-light btn-md w-100 ms-2"
                                    data-bs-dismiss="modal">{{ lang('Close') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
