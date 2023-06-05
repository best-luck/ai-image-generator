@extends('backend.layouts.form')
@section('title', admin_lang('Add New Admin'))
@section('section', admin_lang('Settings'))
@section('container', 'container-max-lg')
@section('back', route('admin.settings.admins.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.admins.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card p-2">
            <div class="card-body">
                <div class="avatar text-center py-4">
                    <img id="filePreview" src="{{ asset('images/avatars/default.png') }}" class="rounded-circle mb-3"
                        width="120px" height="120px">
                    <button id="selectFileBtn" type="button"
                        class="btn btn-secondary d-flex m-auto">{{ admin_lang('Choose Image') }}</button>
                    <input id="selectedFileInput" type="file" name="avatar" accept="image/png, image/jpg, image/jpeg"
                        hidden>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('First Name') }} : <span class="red">*</span></label>
                            <input type="firstname" class="form-control" name="firstname" required autofocus>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Last Name') }} : <span class="red">*</span></label>
                            <input type="lastname" class="form-control" name="lastname" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Email Address') }} : <span class="red">*</span></label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Password') }} : <span class="red">*</span></label>
                    <input type="text" class="form-control" name="password" value="{{ $password }}" required>
                </div>
            </div>
        </div>
    </form>
@endsection
