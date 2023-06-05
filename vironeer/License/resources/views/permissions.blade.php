@extends('vironeer::layouts.app')
@section('title', admin_lang('Permissions'))
@section('content')
    <div class="vironeer-steps-body">
        @foreach ($permissions as $permission)
            <div class="vironeer-steps-req">
                <p class="mb-0"><i class="fas fa-folder-open me-2"></i>{{ str_replace(base_path() . '/', '', $permission) }}
                </p>
                @if (filePermissionValidation($permission))
                    <div class="vironeer-steps-req-success">
                        <i class="fa fa-check"></i>
                    </div>
                @else
                    <div class="vironeer-steps-req-fail">
                        <i class="fa fa-times"></i>
                    </div>
                @endif
            </div>
        @endforeach
        <div class="mt3">
            @if (!$error)
                <div class="alert alert-success">
                    <i class="fa fa-check-circle me-2"></i>
                    {{ admin_lang('Congrats all permissions are enabled you can continue to next step') }}
                </div>
                <form action="{{ route('install.permissions') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-md">{{ admin_lang('Continue') }}<i
                            class="fas fa-arrow-right ms-2"></i></button>
                </form>
            @else
                <div class="alert alert-danger">
                    <i class="fa fa-times-circle me-2"></i>
                    {{ admin_lang('Some permissions are missing please give 0775 permission to all files above.') }}
                </div>
                <button class="btn btn-primary btn-md" disabled>{{ admin_lang('Continue') }}<i
                        class="fas fa-arrow-right ms-2"></i></button>
            @endif
        </div>
    </div>
@endsection
