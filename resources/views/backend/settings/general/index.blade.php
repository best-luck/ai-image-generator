@extends('backend.layouts.form')
@section('title', admin_lang('General Information'))
@section('section', admin_lang('Settings'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.general.update') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card mb-3">
            <div class="card-header">
                {{ admin_lang('General Information') }}
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Site Name') }} : <span class="red">*</span></label>
                        <input type="text" name="general[site_name]" class="form-control"
                            value="{{ $settings->general->site_name }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Site URL') }} : <span class="red">*</span></label>
                        <input type="text" name="general[site_url]" class="form-control"
                            value="{{ $settings->general->site_url }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Contact email') }} : <span class="red">*</span></label>
                        <input type="text" name="general[contact_email]" class="form-control"
                            value="{{ $settings->general->contact_email }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Terms of service') }} : <small
                                class="text-muted">({{ admin_lang('Used on registration & cookies') }})</small></label>
                        <input type="text" name="general[terms_of_service_link]" class="form-control"
                            value="{{ $settings->general->terms_of_service_link }}">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Date format') }} : <span class="red">*</span></label>
                        <select name="general[date_format]" class="form-select">
                            @foreach (dateFormatsArray() as $formatKey => $formatValue)
                                <option value="{{ $formatKey }}"
                                    {{ $formatKey == $settings->general->date_format ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::now()->format($formatValue) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Timezone') }} : <span class="red">*</span></label>
                        <select name="general[timezone]" class="form-select">
                            @foreach (timezonesArray() as $timezoneKey => $timezoneValue)
                                <option value="{{ $timezoneKey }}"
                                    {{ $timezoneKey == $settings->general->timezone ? 'selected' : '' }}>
                                    {{ $timezoneValue }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header bg-primary text-white border-bottom-0">
                {{ admin_lang('AI Api Key') }}
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('API Provider') }} : <span class="red">*</span></label>
                        <select name="ai_api[provider]" class="form-select">
                            <option value="openai" {{ $settings->ai_api->provider == 'openai' ? 'selected' : '' }}>
                                {{ admin_lang('Open AI') }}</option>
                            <option value="stablediffusion"
                                {{ $settings->ai_api->provider == 'stablediffusion' ? 'selected' : '' }}>
                                {{ admin_lang('Stable diffusion') }}</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('API Key') }} : <span class="red">*</span></label>
                        <input type="text" name="ai_api[api_key]" class="form-control"
                            value="{{ demoMode() ? '' : $settings->ai_api->api_key }}">
                    </div>
                </div>
                <li class="mb-2"><strong>{{ admin_lang('Stable diffusion API') }}:</strong> <a
                        href="https://bit.ly/stablediffusionapi" target="_blank">https://stablediffusionapi.com/register</a>
                <li><strong>{{ admin_lang('Openai API') }}:</strong> <a href="https://platform.openai.com/account/api-keys"
                        target="_blank">https://platform.openai.com/account/api-keys</a>
                </li>
                </li>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        {{ admin_lang('Currency') }}
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-2">
                            <div class="col-lg-12">
                                <label class="form-label">{{ admin_lang('Currency Code') }} : <span
                                        class="red">*</span></label>
                                <input type="text" name="currency[code]" class="form-control"
                                    value="{{ $settings->currency->code }}" placeholder="{{ admin_lang('USD') }}"
                                    required>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ admin_lang('Currency Symbol') }} : <span
                                        class="red">*</span></label>
                                <input type="text" name="currency[symbol]" class="form-control"
                                    value="{{ $settings->currency->symbol }}" placeholder="{{ admin_lang('$') }}"
                                    required>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ admin_lang('Currency position') }} : <span
                                        class="red">*</span></label>
                                <select name="currency[position]" class="form-select">
                                    <option value="1" {{ $settings->currency->position == 1 ? 'selected' : '' }}>
                                        {{ admin_lang('Before price') }}</option>
                                    <option value="2" {{ $settings->currency->position == 2 ? 'selected' : '' }}>
                                        {{ admin_lang('After price') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ admin_lang('Subscription') }}</span>
                        <a href="{{ route('admin.settings.mailtemplates.index') }}"
                            class="btn btn-secondary btn-sm">{{ admin_lang('Mail Templates') }}</a>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-2">
                            <div class="col-lg-12">
                                <label class="form-label">{{ admin_lang('Subscription About to expire reminder') }} :
                                    <span class="red">*</span></label>
                                <select name="subscription[about_to_expire_reminder]" class="form-select">
                                    <option value="1"
                                        {{ $settings->subscription->about_to_expire_reminder == 1 ? 'selected' : '' }}>
                                        {{ admin_lang('Before One day') }}</option>
                                    <option value="2"
                                        {{ $settings->subscription->about_to_expire_reminder == 2 ? 'selected' : '' }}>
                                        {{ admin_lang('Before 2 days') }}</option>
                                    <option value="3"
                                        {{ $settings->subscription->about_to_expire_reminder == 3 ? 'selected' : '' }}>
                                        {{ admin_lang('Before 3 days') }}</option>
                                    <option value="7"
                                        {{ $settings->subscription->about_to_expire_reminder == 7 ? 'selected' : '' }}>
                                        {{ admin_lang('Before 7 days') }}</option>
                                    <option value="14"
                                        {{ $settings->subscription->about_to_expire_reminder == 14 ? 'selected' : '' }}>
                                        {{ admin_lang('Before 14 days') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ admin_lang('Subscription Expired reminder') }} : <span
                                        class="red">*</span></label>
                                <select name="subscription[expired_reminder]" class="form-select">
                                    <option value="1"
                                        {{ $settings->subscription->expired_reminder == 1 ? 'selected' : '' }}>
                                        {{ admin_lang('After One day') }}</option>
                                    <option value="2"
                                        {{ $settings->subscription->expired_reminder == 2 ? 'selected' : '' }}>
                                        {{ admin_lang('After 2 days') }}</option>
                                    <option value="3"
                                        {{ $settings->subscription->expired_reminder == 3 ? 'selected' : '' }}>
                                        {{ admin_lang('After 3 days') }}</option>
                                    <option value="7"
                                        {{ $settings->subscription->expired_reminder == 7 ? 'selected' : '' }}>
                                        {{ admin_lang('After 7 days') }}</option>
                                    <option value="14"
                                        {{ $settings->subscription->expired_reminder == 14 ? 'selected' : '' }}>
                                        {{ admin_lang('After 14 days') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ admin_lang('Delete Expired subscriptions') }} : <span
                                        class="red">*</span></label>
                                <select name="subscription[delete_expired]" class="form-select">
                                    <option value="3"
                                        {{ $settings->subscription->delete_expired == 3 ? 'selected' : '' }}>
                                        {{ admin_lang('After 3 days') }}</option>
                                    <option value="7"
                                        {{ $settings->subscription->delete_expired == 7 ? 'selected' : '' }}>
                                        {{ admin_lang('After 7 days') }}</option>
                                    <option value="14"
                                        {{ $settings->subscription->delete_expired == 14 ? 'selected' : '' }}>
                                        {{ admin_lang('After 14 days') }}</option>
                                    <option value="30"
                                        {{ $settings->subscription->delete_expired == 30 ? 'selected' : '' }}>
                                        {{ admin_lang('After 1 Month') }}</option>
                                    <option value="60"
                                        {{ $settings->subscription->delete_expired == 60 ? 'selected' : '' }}>
                                        {{ admin_lang('After 3 Months') }}</option>
                                    <option value="120"
                                        {{ $settings->subscription->delete_expired == 120 ? 'selected' : '' }}>
                                        {{ admin_lang('After 6 Months') }}</option>
                                    <option value="365"
                                        {{ $settings->subscription->delete_expired == 365 ? 'selected' : '' }}>
                                        {{ admin_lang('After 1 Year') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                {{ admin_lang('Colors') }}
            </div>
            <div class="card-body">
                <div class="row g-3 mb-2">
                    @foreach ($settings->colors as $key => $value)
                        <div class="col-lg-3">
                            <label class="form-label capitalize">{{ str_replace('_', ' ', $key) }} : <span
                                    class="red">*</span></label>
                            <div class="vironeer-color-picker input-group">
                                <span class="input-group-text colorpicker-input-addon">
                                    <i></i>
                                </span>
                                <input type="text" name="colors[{{ $key }}]" class="form-control"
                                    value="{{ $value }}" required>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                {{ admin_lang('Actions') }}
            </div>
            <div class="card-body">
                <div class="row g-3 mb-2">
                    <div class="col-xl-4">
                        <label class="form-label">{{ admin_lang('Email Verification') }} :</label>
                        <input type="checkbox" name="actions[email_verification_status]" data-toggle="toggle"
                            {{ $settings->actions->email_verification_status ? 'checked' : '' }}>
                    </div>
                    <div class="col-xl-4">
                        <label class="form-label">{{ admin_lang('Website Registration') }} :</label>
                        <input type="checkbox" name="actions[registration_status]" data-toggle="toggle"
                            {{ $settings->actions->registration_status ? 'checked' : '' }}>
                    </div>
                    <div class="col-xl-4">
                        <label class="form-label">{{ admin_lang('Force SSL') }} : </label>
                        <input type="checkbox" name="actions[force_ssl_status]" data-toggle="toggle"
                            {{ $settings->actions->force_ssl_status ? 'checked' : '' }}>
                    </div>
                    <div class="col-xl-4">
                        <label class="form-label">{{ admin_lang('GDPR Cookie') }} : </label>
                        <input type="checkbox" name="actions[gdpr_cookie_status]" data-toggle="toggle"
                            {{ $settings->actions->gdpr_cookie_status ? 'checked' : '' }}>
                    </div>
                    <div class="col-xl-4">
                        <label class="form-label">{{ admin_lang('Website blog') }} :</label>
                        <input type="checkbox" name="actions[blog_status]" data-toggle="toggle"
                            {{ $settings->actions->blog_status ? 'checked' : '' }}>
                    </div>
                    <div class="col-xl-4">
                        <label class="form-label">{{ admin_lang('Contact Page') }} : </label>
                        <input type="checkbox" name="actions[contact_page]" data-toggle="toggle"
                            data-on="{{ admin_lang('Enable') }}" data-off="{{ admin_lang('Disable') }}"
                            {{ $settings->actions->contact_page ? 'checked' : '' }}>
                    </div>
                    <div class="col-xl-4">
                        <label class="form-label">{{ admin_lang('Features Page') }} : </label>
                        <input type="checkbox" name="actions[features_page]" data-toggle="toggle"
                            data-on="{{ admin_lang('Enable') }}" data-off="{{ admin_lang('Disable') }}"
                            {{ $settings->actions->features_page ? 'checked' : '' }}>
                    </div>
                    <div class="col-xl-4">
                        <label class="form-label">{{ admin_lang('FAQs Status') }} : </label>
                        <input type="checkbox" name="actions[faqs_status]" data-toggle="toggle"
                            {{ $settings->actions->faqs_status ? 'checked' : '' }}>
                    </div>
                    <div class="col-xl-4">
                        <label class="form-label">{{ admin_lang('Include language code in URL') }} : </label>
                        <input type="checkbox" name="actions[language_type]" data-toggle="toggle"
                            data-on="{{ admin_lang('Yes') }}" data-off="{{ admin_lang('No') }}"
                            {{ $settings->actions->language_type ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                {{ admin_lang('Logo & Favicon') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="my-3">
                            <div class="vironeer-image-preview bg-light">
                                <img id="vironeer-preview-img-1" src="{{ asset($settings->media->dark_logo) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <input id="vironeer-image-targeted-input-1" type="file" name="media[dark_logo]"
                                accept=".jpg, .jpeg, .png, .svg" class="form-control" hidden>
                            <button data-id="1" type="button"
                                class="vironeer-select-image-button btn btn-secondary btn-lg w-100 mb-2">{{ admin_lang('Choose Dark Logo') }}</button>
                            <small class="text-muted">{{ admin_lang('Supported (PNG, JPG, JPEG, SVG)') }}</small>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="my-3">
                            <div class="vironeer-image-preview bg-dark">
                                <img id="vironeer-preview-img-2" src="{{ asset($settings->media->light_logo) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <input id="vironeer-image-targeted-input-2" type="file" name="media[light_logo]"
                                accept=".jpg, .jpeg, .png, .svg" class="form-control" hidden>
                            <button data-id="2" type="button"
                                class="vironeer-select-image-button btn btn-secondary btn-lg w-100 mb-2">{{ admin_lang('Choose Light Logo') }}</button>
                            <small class="text-muted">{{ admin_lang('Supported (PNG, JPG, JPEG, SVG)') }}</small>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="my-3">
                            <div class="vironeer-image-preview bg-light">
                                <img id="vironeer-preview-img-3" src="{{ asset($settings->media->favicon) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <input id="vironeer-image-targeted-input-3" type="file" name="media[favicon]"
                                accept=".jpg, .jpeg, .png, .ico" class="form-control" hidden>
                            <button data-id="3" type="button"
                                class="vironeer-select-image-button btn btn-secondary btn-lg w-100 mb-2">{{ admin_lang('Choose Favicon') }}</button>
                            <small class="text-muted">{{ admin_lang('Supported (PNG, JPG, JPEG, ICO)') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        {{ admin_lang('Social Image') }} <small
                            class="text-muted">{{ admin_lang('(og:image)') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <div class="vironeer-image-preview-box bg-light">
                                <img id="vironeer-preview-img-4" src="{{ asset($settings->media->social_image) }}"
                                    width="100%" height="315px">
                            </div>
                        </div>
                        <div class="mb-3">
                            <input id="vironeer-image-targeted-input-4" type="file" name="media[social_image]"
                                accept="image/jpg, image/jpeg" class="form-control" hidden>
                            <button data-id="4" type="button"
                                class="vironeer-select-image-button btn btn-secondary btn-lg w-100 mb-2">{{ admin_lang('Choose Social Image') }}</button>
                            <small class="text-muted">
                                {{ admin_lang('Supported (JPEG, JPG) Size') }} <strong>600x315px.</strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    @endpush
    @push('scripts')
        <script>
            $(function() {
                $('.vironeer-color-picker').colorpicker();
            });
        </script>
    @endpush
@endsection
