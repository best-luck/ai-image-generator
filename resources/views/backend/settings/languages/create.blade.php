@extends('backend.layouts.form')
@section('title', admin_lang('Add language'))
@section('section', admin_lang('Settings'))
@section('container', 'container-max-lg')
@section('back', route('admin.settings.languages.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.languages.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="vironeer-file-preview-box mb-3 bg-light p-4 text-center">
                    <div class="file-preview-box mb-3 d-none">
                        <img id="filePreview" src="#" class="rounded-3" width="100" height="100">
                    </div>
                    <button id="selectFileBtn" type="button"
                        class="btn btn-secondary mb-2">{{ admin_lang('Choose Flag') }}</button>
                    <input id="selectedFileInput" type="file" name="flag" accept="image/png, image/jpg, image/jpeg"
                        hidden required>
                    <small class="text-muted d-block">{{ admin_lang('Allowed (PNG, JPG, JPEG)') }}</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Name') }} : <span class="red">*</span></label>
                    <input type="text" name="name" class="form-control" required autofocus>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Code') }} : <span class="red">*</span></label>
                        <select name="code" class="form-select select2" required>
                            <option></option>
                            @foreach (languages() as $code => $name)
                                <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Direction') }} : <span class="red">*</span></label>
                        <select name="direction" class="form-select">
                            <option value="1">{{ admin_lang('LTR') }}</option>
                        </select>
                    </div>
                </div>
                <div class="mb-0 form-check">
                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default">
                    <label class="form-check-label" for="is_default">{{ admin_lang('Default language') }}</label>
                </div>
            </div>
        </div>
    </form>
@endsection
