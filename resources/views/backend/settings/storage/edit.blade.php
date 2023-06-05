@extends('backend.layouts.form')
@section('title', admin_lang('Edit | ') . $storageProvider->name)
@section('section', admin_lang('Settings'))
@section('back', route('admin.settings.storage.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.storage.update', $storageProvider->id) }}"
        method="POST">
        @csrf
        <div class="card custom-card mb-4">
            <div class="card-body">
                <div class="vironeer-file-preview-box bg-light mb-3 p-4 text-center">
                    <div class="file-preview-box mb-3">
                        <img id="filePreview" src="{{ asset($storageProvider->logo) }}" height="100px">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Name') }} : </label>
                        <input class="form-control" value="{{ $storageProvider->name }}" readonly>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Status') }} :</label>
                        <input type="checkbox" name="status" data-toggle="toggle"
                            @if ($storageProvider->status) checked @endif>
                    </div>
                </div>
            </div>
        </div>
        @if ($storageProvider->instructions)
            <div class="card custom-card mb-4">
                <div class="card-header">
                    <i class="far fa-question-circle me-2"></i>{{ $storageProvider->name . admin_lang(' Instructions') }}
                </div>
                <div class="card-body">
                    {!! str_replace('[URL]', url('/'), $storageProvider->instructions) !!}
                </div>
            </div>
        @endif
        @if ($storageProvider->credentials)
            <div class="card custom-card mb-4">
                <div class="card-header">
                    <i class="fa fa-key me-2"></i> {{ $storageProvider->name . admin_lang(' Credentials') }}
                </div>
                <div class="card-body">
                    <div class="row g-3 pb-2">
                        @foreach ($storageProvider->credentials as $key => $value)
                            <div class="col-lg-12">
                                <label class="form-label capitalize">
                                    {{ str_replace('_', ' ', $key) }} :
                                </label>
                                <input type="text" name="credentials[{{ $key }}]"
                                    value="{{ demoMode() ? '' : $value }}" class="form-control remove-spaces">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </form>
    @if ($storageProvider->status)
        <div class="card custom-card">
            <div class="card-header">
                <i class="fas fa-hdd me-2"></i> {{ admin_lang('Test Connection') }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.storage.test', $storageProvider->id) }}" method="POST">
                    @csrf
                    <button
                        class="vironeer-form-confirm btn btn-success">{{ admin_lang('Test ') . $storageProvider->name }}
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection
