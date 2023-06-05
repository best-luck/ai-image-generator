@extends('backend.layouts.form')
@section('title', 'Translate > ' . $language->name)
@section('section', admin_lang('Settings'))
@section('back', route('admin.settings.languages.index'))
@section('content')
    <div class="note note-warning d-flex">
        <div class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div>
            <strong>{{ admin_lang('Important!') }}</strong><br>
            <small>{{ admin_lang('There are some words that should not be translated that start with some tags or are inside a tag') }}
                <strong>{{ admin_lang(':value, :seconds, :min, ::max, {username}') }}</strong> {{ admin_lang('etc...') }}</small>
        </div>
    </div>
    <div class="mb-3">
        <form class="d-inline" action="{{ route('admin.settings.languages.translates.export', $language->code) }}"
            method="POST">
            @csrf
            <button class="btn btn-success btn-lg me-2"><i class="fas fa-download me-2"></i>{{ admin_lang('Export') }}</button>
        </form>
        <button class="btn btn-blue btn-lg" data-bs-toggle="modal" data-bs-target="#importModal"><i
                class="fas fa-upload me-2"></i>{{ admin_lang('Import') }}</button>
    </div>
    <div class="card translate-card">
        <div class="card-header">
            <ul class="nav {{ count($groups) > 13 ? 'nav-pills' : 'nav-tabs' }} card-header-tabs">
                @foreach ($groups as $group)
                    <li class="nav-item">
                        <a class="nav-link {{ $active == $group ? 'active' : '' }}"
                            href="{{ route('admin.settings.languages.translates.group', [$language->code, $group]) }}">{{ str_replace('-', ' ', $group) }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card-body my-1">
            <form id="vironeer-submited-form"
                action="{{ route('admin.settings.languages.translates.update', $language->id) }}" method="POST">
                @csrf
                <input type="hidden" name="group" value="{{ $active }}">
                @if (is_array($translates) && count($translates) > 0)
                    @foreach ($translates as $key1 => $value1)
                        @if (is_array($value1))
                            <h2 class="header">{{ $key1 }}</h2>
                            @foreach ($value1 as $key2 => $value2)
                                <div class="vironeer-translated-item d-block d-lg-flex bd-highlight align-items-center">
                                    <div class="flex-grow-1 bd-highlight">
                                        <textarea id="autosizeInput" class="vironeer-translate-key translate-fields form-control" rows="1" readonly>{{ $defaultLanguage[$key1][$key2] }}</textarea>
                                    </div>
                                    <div class="pe-3 ps-3 bd-highlight text-center text-success d-none d-lg-block"><i
                                            class="fas fa-chevron-right"></i></div>
                                    <div class="flex-grow-1 bd-highlight">
                                        <textarea id="autosizeInput" name="translates[{{ $key1 }}][{{ $key2 }}]"
                                            class="translate-fields form-control" rows="1" placeholder="{{ $value2 }}">{{ $value2 }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="vironeer-translated-item d-block d-lg-flex bd-highlight align-items-center">
                                <div class="flex-grow-1 bd-highlight">
                                    <textarea id="autosizeInput" class="vironeer-translate-key translate-fields form-control" rows="1" readonly>{{ $defaultLanguage[$key1] }}</textarea>
                                </div>
                                <div class="pe-3 ps-3 bd-highlight text-center text-success d-none d-lg-block"><i
                                        class="fas fa-chevron-right"></i></div>
                                <div class="flex-grow-1 bd-highlight">
                                    <textarea id="autosizeInput" name="translates[{{ $key1 }}]" class="translate-fields form-control"
                                        rows="1" placeholder="{{ $value1 }}">{{ $value1 }}</textarea>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center">
                        <p class="mb-0 text-muted">{{ admin_lang('No translations found') }}</p>
                    </div>
                @endif
            </form>
        </div>
    </div>
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">{{ $language->name }} <i
                            class="fas fa-angle-right ms-1 me-1"></i> {{ admin_lang('Import') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.settings.languages.translates.import', $language->code) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="alert alert-warning">
                            <h5 class="mb-2"><strong>{{ admin_lang('Important!') }}</strong></h5>
                            <p class="mb-2">
                                {{ admin_lang('Existing translations will be permanently deleted, make sure to take a backup before importing the new translations.') }}
                            </p>
                            <p class="mb-0">
                                {{ admin_lang('Uploading files other than translations exported may cause errors on your site, make sure you are importing the correct files.') }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Language File (ZIP)') }} : <span
                                    class="red">*</span></label>
                            <input type="file" name="language_file" class="form-control">
                        </div>
                        <button class="btn btn-primary">{{ admin_lang('Import') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/autosize/autosize.min.js') }}"></script>
    @endpush
    @push('scripts')
        <script>
            autosize($('textarea'));
        </script>
    @endpush
@endsection
