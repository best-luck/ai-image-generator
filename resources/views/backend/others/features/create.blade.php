@extends('backend.layouts.form')
@section('title', admin_lang('Create feature'))
@section('back', route('admin.features.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.features.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card p-2 mb-3">
            <div class="card-body">
                <div class="vironeer-file-preview-box mb-3 bg-light p-5 text-center">
                    <div class="file-preview-box mb-3 d-none">
                        <img id="filePreview" src="#" class="rounded-3" width="150" height="150">
                    </div>
                    <button id="selectFileBtn" type="button"
                        class="btn btn-secondary mb-2">{{ admin_lang('Choose Image') }}</button>
                    <input id="selectedFileInput" type="file" name="image" accept="image/png, image/jpg, image/jpeg"
                        hidden required>
                    <small class="text-muted d-block">{{ admin_lang('Allowed (PNG, JPG, JPEG)') }}</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Language') }} :<span class="red">*</span></label>
                    <select id="articleLang" name="lang" class="form-select select2" required>
                        <option></option>
                        @foreach ($adminLanguages as $adminLanguage)
                            <option value="{{ $adminLanguage->code }}" @if (old('lang') == $adminLanguage->code) selected @endif>
                                {{ $adminLanguage->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Feature title') }} : <span class="red">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required />
                </div>
                <div class="mb-2">
                    <label class="form-label">{{ admin_lang('Feature content') }} :
                        <small class="text-muted">({{ admin_lang('Max 600 characters') }})</small><span
                            class="red">*</span></label>
                    <textarea name="content" rows="10" class="form-control" required>{{ old('content') }}</textarea>
                </div>
            </div>
        </div>
    </form>
@endsection
