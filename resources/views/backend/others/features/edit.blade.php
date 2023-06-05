@extends('backend.layouts.form')
@section('title', $feature->title)
@section('back', route('admin.features.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.features.update', $feature->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card p-2 mb-3">
            <div class="card-body">
                <div class="vironeer-file-preview-box mb-3 bg-light p-5 text-center">
                    <div class="file-preview-box mb-3">
                        <img id="filePreview" src="{{ asset($feature->image) }}" class="rounded-3" width="150"
                            height="150">
                    </div>
                    <button id="selectFileBtn" type="button"
                        class="btn btn-secondary mb-2">{{ admin_lang('Choose Image') }}</button>
                    <input id="selectedFileInput" type="file" name="image" accept="image/png, image/jpg, image/jpeg"
                        hidden>
                    <small class="text-muted d-block">{{ admin_lang('Allowed (PNG, JPG, JPEG)') }}</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Language') }} :<span class="red">*</span></label>
                    <select id="articleLang" name="lang" class="form-select select2" required>
                        <option></option>
                        @foreach ($adminLanguages as $adminLanguage)
                            <option value="{{ $adminLanguage->code }}" @if ($feature->lang == $adminLanguage->code) selected @endif>
                                {{ $adminLanguage->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Feature title') }} : <span class="red">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ $feature->title }}" required />
                </div>
                <div class="mb-2">
                    <label class="form-label">{{ admin_lang('Feature content') }} :
                        <small class="text-muted">({{ admin_lang('Max 600 characters, spaces allowed') }})</small><span
                            class="red">*</span></label>
                    <textarea name="content" rows="10" class="form-control" required>{{ $feature->content }}</textarea>
                </div>
            </div>
        </div>
    </form>
@endsection
