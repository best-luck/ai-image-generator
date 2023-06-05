@extends('backend.layouts.form')
@section('title', admin_lang('Create Page'))
@section('section', admin_lang('Settings'))
@section('back', route('admin.settings.pages.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.pages.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card p-2 mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Page title') }} : <span class="red">*</span></label>
                            <input type="text" name="title" id="create_slug" class="form-control"
                                value="{{ old('title') }}" required autofocus />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Slug') }} : <span class="red">*</span></label>
                            <div class="input-group vironeer-input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ url('/') }}/</span>
                                </div>
                                <input type="text" name="slug" id="show_slug" class="form-control"
                                    value="{{ old('slug') }}" required />
                            </div>
                        </div>
                        <div class="ckeditor-lg mb-3">
                            <label class="form-label">{{ admin_lang('Page content') }} : <span
                                    class="red">*</span></label>
                            <textarea name="content" rows="10" class="form-control ckeditor">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Language') }} :<span class="red">*</span></label>
                            <select name="lang" class="form-select select2" required>
                                <option></option>
                                @foreach ($adminLanguages as $adminLanguage)
                                    <option value="{{ $adminLanguage->code }}"
                                        @if (old('lang') == $adminLanguage->code) selected @endif>
                                        {{ $adminLanguage->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
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
            let GET_SLUG_URL = "{{ route('admin.settings.pages.slug') }}";
        </script>
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/ckeditor/plugins/uploadAdapterPlugin.js') }}"></script>
    @endpush
@endsection
