@extends('backend.layouts.application')
@section('title', admin_lang('System Information'))
@section('content')
    <div class="card mb-4 custom-card">
        <div class="card-header bg-lg-1 text-white border-bottom-0"><i
                class="fas fa-folder me-2"></i>{{ admin_lang('Application') }}</div>
        <ul class="custom-list-group system list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <strong>{{ admin_lang('Name') }}</strong>
                <span class="capitalize">{{ str_replace('_', ' ', $system->application->name) }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                <strong>{{ admin_lang('Version') }}</strong>
                <span>v{{ $system->application->version }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center ">
                <strong>{{ admin_lang('Laravel Version') }}</strong>
                <span>v{{ $system->application->laravel }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                <strong>{{ admin_lang('Timezone') }}</strong>
                <span class="capitalize">{{ $system->application->timezone }}</span>
            </li>
        </ul>
    </div>
    <div class="card custom-card mb-4">
        <div class="card-header bg-lg-5 text-white border-bottom-0"><i
                class="fas fa-server me-2"></i>{{ admin_lang('Server Details') }}</div>
        <ul class="custom-list-group system list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <strong>{{ admin_lang('Software') }}</strong>
                <span>{{ $system->server->SERVER_SOFTWARE }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                <strong>{{ admin_lang('PHP Version') }}</strong>
                <span>v{{ $system->server->php }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <strong>{{ admin_lang('IP Address') }}</strong>
                <span>{{ $system->server->SERVER_ADDR }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                <strong>{{ admin_lang('Protocol') }}</strong>
                <span>{{ $system->server->SERVER_PROTOCOL }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <strong>{{ admin_lang('HTTP Host') }}</strong>
                <span>{{ $system->server->HTTP_HOST }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                <strong>{{ admin_lang('Port') }}</strong>
                <span>{{ $system->server->SERVER_PORT }}</span>
            </li>
        </ul>
    </div>
    <div class="card custom-card">
        <div class="card-header bg-lg-14 text-white border-bottom-0"><i
                class="fas fa-database me-2"></i>{{ admin_lang('System Cache') }}</div>
        <ul class="custom-list-group system list-group list-group-flush">
            <li class="list-group-item">
                <i class="far fa-check-circle me-2 text-success"></i>
                <span>{{ admin_lang('Compiled views will be cleared') }}</span>
            </li>
            <li class="list-group-item">
                <i class="far fa-check-circle me-2 text-success"></i>
                <span>{{ admin_lang('Application cache will be cleared') }}</span>
            </li>
            <li class="list-group-item">
                <i class="far fa-check-circle me-2 text-success"></i>
                <span>{{ admin_lang('Route cache will be cleared') }}</span>
            </li>
            <li class="list-group-item">
                <i class="far fa-check-circle me-2 text-success"></i>
                <span>{{ admin_lang('Configuration cache will be cleared') }}</span>
            </li>
            <li class="list-group-item">
                <i class="far fa-check-circle me-2 text-success"></i>
                <span>{{ admin_lang('All Other Caches will be cleared') }}</span>
            </li>
            <li class="list-group-item">
                <i class="far fa-check-circle me-2 text-success"></i>
                <span>{{ admin_lang('Error logs file will be cleared') }}</span>
            </li>
            <li class="list-group-item p-0"></li>
        </ul>
        <div class="card-body">
            <form action="{{ route('admin.system.info.cache') }}" method="POST">
                @csrf
                <button class="btn btn-danger btn-lg w-100 vironeer-form-confirm"><i
                        class="far fa-trash-alt me-2"></i>{{ admin_lang('Clear System Cache') }}</button>
            </form>
        </div>
    </div>
@endsection
