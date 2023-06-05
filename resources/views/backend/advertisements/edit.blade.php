@extends('backend.layouts.form')
@section('title',
    $advertisement->alias == 'head_code'
    ? admin_lang('Edit ') . $advertisement->position
    : admin_lang('Edit
    Advertisement | ') . $advertisement->position)
@section('back', route('admin.advertisements.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.advertisements.update', $advertisement->id) }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><strong>{{ $advertisement->position }}</strong> {!! $advertisement->size ? '- (' . $advertisement->size . ')' : '' !!}</span>
                <span class="col-3">
                    <input type="checkbox" name="status" data-toggle="toggle"
                        @if ($advertisement->status) checked @endif>
                </span>
            </div>
            <div class="card-body">
                <div class="mb-0">
                    <textarea id="jsContent" name="code" class="form-control" rows="10">{{ $advertisement->code }}</textarea>
                </div>
            </div>
        </div>
    </form>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/codemirror/codemirror.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/codemirror/monokai.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/codemirror/codemirror.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/codemirror/htmlmixed.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/codemirror/xml.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/codemirror/javascript.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/codemirror/sublime.min.js') }}"></script>
    @endpush
@endsection
