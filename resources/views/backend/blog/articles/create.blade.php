@extends('backend.layouts.form')
@section('section', admin_lang('Blog'))
@section('title', admin_lang('Create Article'))
@section('back', route('articles.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card p-2 mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Article title') }} : <span
                                    class="red">*</span></label>
                            <input type="text" name="title" id="create_slug" class="form-control"
                                value="{{ old('title') }}" required autofocus />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Slug') }} : <span class="red">*</span></label>
                            <div class="input-group vironeer-input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ url('blog/articles/') }}/</span>
                                </div>
                                <input type="text" name="slug" id="show_slug" class="form-control"
                                    value="{{ old('slug') }}" required />
                            </div>
                        </div>
                        <div class="ckeditor-lg mb-0">
                            <label class="form-label">{{ admin_lang('Article content') }} : <span
                                    class="red">*</span></label>
                            <textarea name="content" rows="10" class="form-control ckeditor">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-2 mb-3">
                    <div class="card-body">
                        <div class="vironeer-file-preview-box mb-3 bg-light p-5 text-center">
                            <div class="file-preview-box mb-3 d-none">
                                <img id="filePreview" src="#" class="rounded-3 w-100" height="160px">
                            </div>
                            <button id="selectFileBtn" type="button"
                                class="btn btn-secondary mb-2">{{ admin_lang('Choose Image') }}</button>
                            <input id="selectedFileInput" type="file" name="image"
                                accept="image/png, image/jpg, image/jpeg" hidden required>
                            <small class="text-muted d-block">{{ admin_lang('Allowed (PNG, JPG, JPEG)') }}</small>
                            <small
                                class="text-muted d-block">{{ admin_lang('Image will be resized into (1280x720)') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Language') }} :<span class="red">*</span></label>
                            <select id="articleLang" name="lang" class="form-select select2" required>
                                <option></option>
                                @foreach ($adminLanguages as $adminLanguage)
                                    <option value="{{ $adminLanguage->code }}"
                                        @if (old('lang') == $adminLanguage->code) selected @endif>
                                        {{ $adminLanguage->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Article category') }} : <span
                                    class="red">*</span></label>
                            <select id="articleCategory" name="category" class="form-select" required>
                                <option value="" selected disabled>{{ admin_lang('Choose') }}</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ admin_lang('Short description') }} : <span
                                    class="red">*</span></label>
                            <textarea name="short_description" rows="6" class="form-control"
                                placeholder="{{ admin_lang('50 to 200 character at most') }}" required>{{ old('short_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('top_scripts')
        <script>
            "use strict";
            let GET_SLUG_URL = "{{ route('articles.slug') }}";
        </script>
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/ckeditor/plugins/uploadAdapterPlugin.js') }}"></script>
    @endpush
@endsection
