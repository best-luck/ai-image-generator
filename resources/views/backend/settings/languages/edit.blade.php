@extends('backend.layouts.form')
@section('title', admin_lang('Edit Language | ') . $language->name)
@section('section', admin_lang('Settings'))
@section('container', 'container-max-lg')
@section('back', route('admin.settings.languages.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.languages.update', $language->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="vironeer-file-preview-box mb-3 bg-light p-4 text-center">
                    <div class="file-preview-box mb-3">
                        <img id="filePreview" src="{{ asset($language->flag) }}" class="rounded-3" alt="{{ $language->name }}"
                            width="100" height="100">
                    </div>
                    <button id="selectFileBtn" type="button"
                        class="btn btn-secondary mb-2">{{ admin_lang('Choose Flag') }}</button>
                    <input id="selectedFileInput" type="file" name="flag" accept="image/png, image/jpg, image/jpeg"
                        hidden>
                    <small class="text-muted d-block">{{ admin_lang('Allowed (PNG, JPG, JPEG)') }}</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Name') }} : <span class="red">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $language->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Direction') }} : <span class="red">*</span></label>
                    <select name="direction" class="form-select">
                        <option value="1" {{ $language->direction == 1 ? 'selected' : '' }}>{{ admin_lang('LTR') }}
                        </option>
                    </select>
                </div>
                <div class="mb-0 form-check">
                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default"
                        {{ env('DEFAULT_LANGUAGE') == $language->code ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_default">{{ admin_lang('Default language') }}</label>
                </div>
            </div>
        </div>
    </form>
@endsection
