@extends('backend.layouts.form')
@section('title', admin_lang('Edit | ') . $gateway->name)
@section('section', admin_lang('Settings'))
@section('back', route('admin.settings.gateways.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.gateways.update', $gateway->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="vironeer-file-preview-box bg-light mb-3 p-4 text-center">
                            <div class="file-preview-box mb-3">
                                <img id="filePreview" src="{{ asset($gateway->logo) }}" class="rounded-3" height="40">
                            </div>
                            <button id="selectFileBtn" type="button"
                                class="btn btn-secondary mb-2">{{ admin_lang('Choose logo') }}</button>
                            <input id="selectedFileInput" type="file" name="logo"
                                accept="image/png, image/jpg, image/jpeg" hidden>
                            <small
                                class="text-muted d-block">{{ admin_lang('Allowed (PNG, JPG, JPEG) Size (300x100)') }}</small>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-lg-12">
                                <label class="form-label">{{ admin_lang('Name') }} :<span class="red">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $gateway->name }}"
                                    required>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ admin_lang('Gateway fees') }} : <span
                                        class="red">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="gateway_fees" class="form-control" placeholder="0"
                                        value="{{ $gateway->fees }}">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">{{ admin_lang('Status') }} :</label>
                                <input id="gatewayStatus" type="checkbox" name="status" data-toggle="toggle"
                                    @if ($gateway->status) checked @endif>
                            </div>
                            @if (!is_null($gateway->test_mode))
                                <div class="col-lg-12">
                                    <label class="form-label">{{ admin_lang('Test Mode') }} :</label>
                                    <input type="checkbox" name="test_mode" data-toggle="toggle"
                                        data-on="{{ admin_lang('Enabled') }}" data-off="{{ admin_lang('Disabled') }}"
                                        @if ($gateway->test_mode) checked @endif>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-key me-2"></i> {{ $gateway->name . admin_lang(' Credentials') }}
                    </div>
                    <div id="credentials" class="card-body">
                        <div class="row g-3 pb-2">
                            @foreach ($gateway->credentials as $key => $value)
                                <div class="col-lg-12">
                                    <label class="form-label capitalize">{{ $gateway->name }}
                                        {{ str_replace('_', ' ', $key) }} :</label>
                                    <input type="text" name="credentials[{{ $key }}]"
                                        value="{{ demoMode() ? '' : $value }}" class="form-control">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($gateway->instructions)
            <div class="card custom-card mt-4">
                <div class="card-header">
                    <i class="far fa-question-circle me-2"></i>{{ $gateway->name . admin_lang(' Instructions') }}
                </div>
                <div class="card-body">
                    {!! str_replace('[URL]', url('/'), $gateway->instructions) !!}
                </div>
            </div>
        @endif
    </form>
@endsection
