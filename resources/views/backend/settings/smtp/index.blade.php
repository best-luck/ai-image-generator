@extends('backend.layouts.form')
@section('title', admin_lang('SMTP'))
@section('section', admin_lang('Settings'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.smtp.update') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                {{ admin_lang('SMTP details') }}
            </div>
            <div class="card-body">
                <div class="mb-3 row">
                    <label class="form-label col-12 col-lg-3 col-form-label">{{ admin_lang('Status :') }} </label>
                    <div class="col col-lg-3">
                        <input type="checkbox" name="smtp[status]" data-toggle="toggle"
                            @if ($settings->smtp->status) checked @endif>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="form-label col-12 col-lg-3 col-form-label">{{ admin_lang('Mail mailer :') }} </label>
                    <div class="col">
                        <select name="smtp[mailer]" class="form-select">
                            <option value="smtp" @if ($settings->smtp->mailer == 'mail_mailer') selected @endif>
                                {{ admin_lang('SMTP') }}
                            </option>
                            <option value="sendmail" @if ($settings->smtp->mailer == 'sendmail') selected @endif>
                                {{ admin_lang('SENDMAIL') }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="form-label col-12 col-lg-3 col-form-label">{{ admin_lang('Mail Host :') }} </label>
                    <div class="col">
                        <input type="text" name="smtp[host]" class="remove-spaces form-control"
                            value="{{ demoMode() ? '' : $settings->smtp->host }}"
                            placeholder="{{ admin_lang('Enter mail host') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="form-label col-12 col-lg-3 col-form-label">{{ admin_lang('Mail Port :') }} </label>
                    <div class="col">
                        <input type="text" name="smtp[port]" class="remove-spaces form-control"
                            value="{{ demoMode() ? '' : $settings->smtp->port }}"
                            placeholder="{{ admin_lang('Enter mail port') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="form-label col-12 col-lg-3 col-form-label">{{ admin_lang('Mail username :') }} </label>
                    <div class="col">
                        <input type="text" name="smtp[username]" class="form-control remove-spaces"
                            value="{{ demoMode() ? '' : $settings->smtp->username }}"
                            placeholder="{{ admin_lang('Enter username') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="form-label col-12 col-lg-3 col-form-label">{{ admin_lang('Mail password :') }} </label>
                    <div class="col">
                        <input type="password" name="smtp[password]" class="form-control"
                            value="{{ demoMode() ? '' : $settings->smtp->password }}"
                            placeholder="{{ admin_lang('Enter password') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="form-label col-12 col-lg-3 col-form-label">{{ admin_lang('Mail encryption :') }} </label>
                    <div class="col">
                        <select name="smtp[encryption]" class="form-select">
                            <option value="tls" @if ($settings->smtp->encryption == 'tls') selected @endif>
                                {{ admin_lang('TLS') }}
                            </option>
                            <option value="ssl" @if ($settings->smtp->encryption == 'ssl') selected @endif>
                                {{ admin_lang('SSL') }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="form-label col-12 col-lg-3 col-form-label">{{ admin_lang('From email :') }} </label>
                    <div class="col">
                        <input type="text" name="smtp[from_email]" class="remove-spaces form-control"
                            value="{{ demoMode() ? '' : $settings->smtp->from_email }}"
                            placeholder="{{ admin_lang('Enter from email') }}">
                    </div>
                </div>
                <div class="row">
                    <label class="form-label col-12 col-lg-3 col-form-label">{{ admin_lang('From name :') }} </label>
                    <div class="col">
                        <input type="text" name="smtp[from_name]" class="remove-spaces form-control"
                            value="{{ demoMode() ? '' : $settings->smtp->from_name }}"
                            placeholder="{{ admin_lang('Enter from name') }}">
                    </div>
                </div>
            </div>
        </div>
    </form>
    @if ($settings->smtp->status)
        <div class="card mt-4">
            <div class="card-header">
                {{ admin_lang('Testing') }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.smtp.test') }}" method="POST">
                    @csrf
                    <div class="row align-items-center">
                        <div class="col-lg-auto">
                            <label class="form-label">{{ admin_lang('E-mail Address') }} : <span
                                    class="red">*</span></label>
                        </div>
                        <div class="col">
                            <input type="email" name="email" class="form-control" placeholder="john@example.com"
                                value="{{ adminAuthInfo()->email }}">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-success">{{ admin_lang('Send') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
