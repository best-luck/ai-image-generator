@extends('backend.layouts.form')
@section('title', admin_lang('Languages'))
@section('section', admin_lang('Settings'))
@section('link', route('admin.settings.languages.create'))
@section('container', 'container-max-lg')
@if ($languages->count() == 0)
    @section('btn_action', 'disabled')
@endif
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.languages.sort') }}" method="POST">
        @csrf
        <input name="ids" id="ids" value="" hidden>
    </form>
    <div class="card">
        <ul class="vironeer-sort-menu custom-list-group list-group list-group-flush">
            @foreach ($languages as $language)
                <li data-id="{{ $language->id }}"
                    class="list-group-item d-flex justify-content-between align-items-center language-list-group-item">
                    <div class="item-title">
                        <span class="vironeer-navigation-handle me-2 text-muted"><i class="fas fa-arrows-alt"></i></span>
                        <img class="flag" src="{{ asset($language->flag) }}" alt="{{ $language->name }}" width="25"
                            height="25">
                        <span>{{ $language->name }}</span>
                        <small
                            class="text-muted">{{ env('DEFAULT_LANGUAGE') == $language->code ? admin_lang('(Default)') : '' }}</small>
                    </div>
                    <div class="buttons">
                        <a href="{{ route('admin.settings.languages.translates', $language->code) }}"
                            class="btn btn-blue btn-sm me-2">
                            <i class="fas fa-language"></i>
                            <span class="ms-2 d-none d-lg-inline">{{ admin_lang('Translate') }}</span>
                        </a>
                        <a class="btn btn-success btn-sm me-2"
                            href="{{ route('admin.settings.languages.edit', $language->id) }}">
                            <i class="fa fa-edit"></i>
                            <span class="ms-2 d-none d-lg-inline">{{ admin_lang('Edit') }}</span>
                        </a>
                        @if ($language->code != env('DEFAULT_LANGUAGE'))
                            <form class="d-inline" action="{{ route('admin.settings.languages.destroy', $language->id) }}"
                                method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="vironeer-able-to-delete btn btn-danger btn-sm">
                                    <i class="far fa-trash-alt"></i>
                                    <span class="ms-2 d-none d-lg-inline">{{ admin_lang('Delete') }}</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    @if ($languages->count() > 0)
        @push('styles_libs')
            <link href="{{ asset('assets/vendor/libs/jquery/jquery-ui.min.css') }}" />
        @endpush
        @push('scripts_libs')
            <script src="{{ asset('assets/vendor/libs/jquery/jquery-ui.min.js') }}"></script>
        @endpush
    @endif
@endsection
