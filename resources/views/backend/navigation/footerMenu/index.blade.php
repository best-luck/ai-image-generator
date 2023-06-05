@extends('backend.layouts.form')
@section('title', $active . ' ' . admin_lang('Footer Menu'))
@section('container', 'container-max-lg')
@section('link', route('admin.footerMenu.create'))
@section('language', true)
@if ($footerMenuLinks->count() == 0)
    @section('btn_action', 'disabled')
@endif
@section('content')
    @if ($footerMenuLinks->count() > 0)
        <form id="vironeer-submited-form" action="{{ route('admin.footerMenu.nestable') }}" method="POST">
            @csrf
            <input name="ids" id="ids" hidden>
        </form>
        <div class="card border-0">
            <div class="dd nestable">
                <ol class="dd-list">
                    @foreach ($footerMenuLinks as $footerMenuLink)
                        <li class="dd-item" data-id="{{ $footerMenuLink->id }}">
                            <div class="dd-handle">
                                <span class="drag-indicator"></span>
                                <span class="dd-title">{{ $footerMenuLink->name }}</span>
                                <div class="dd-nodrag ms-auto">
                                    <a href="{{ route('admin.footerMenu.edit', $footerMenuLink->id) }}"
                                        class="btn btn-sm btn-blue me-2"><i class="fa fa-edit"></i></a>
                                    <form class="d-inline"
                                        action="{{ route('admin.footerMenu.destroy', $footerMenuLink->id) }}"
                                        method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button class="vironeer-able-to-delete btn btn-sm btn-danger"><i
                                                class="far fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </div>
                            @if (count($footerMenuLink->children))
                                <ol class="dd-list">
                                    @foreach ($footerMenuLink->children as $child)
                                        <li class="dd-item" data-id="{{ $child->id }}">
                                            <div class="dd-handle">
                                                <span class="drag-indicator"></span>
                                                <span class="dd-title">{{ $child->name }}</span>
                                                <div class="dd-nodrag ms-auto">
                                                    <a href="{{ route('admin.footerMenu.edit', $child->id) }}"
                                                        class="btn btn-sm btn-blue me-2"><i class="fa fa-edit"></i></a>
                                                    <form class="d-inline"
                                                        action="{{ route('admin.footerMenu.destroy', $child->id) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button class="vironeer-able-to-delete btn btn-sm btn-danger"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                @include('backend.includes.empty')
            </div>
        </div>
    @endif
    @if ($footerMenuLinks->count() > 0)
        @push('styles_libs')
            <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery/nestable/jquery.nestable.min.css') }}">
        @endpush
        @push('scripts_libs')
            <script src="{{ asset('assets/vendor/libs/jquery/nestable/jquery.nestable.min.js') }}"></script>
        @endpush
    @endif
@endsection
