@extends('backend.layouts.form')
@section('section', admin_lang('Account'))
@section('title', admin_lang('Change password'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.account.security.update') }}" method="POST">
        @csrf
        <div class="card p-2">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Password') }} : <span class="red">*</span></label>
                    <input type="password" class="form-control form-control-lg" name="current-password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('New Password') }} : <span class="red">*</span></label>
                    <input type="password" class="form-control form-control-lg" name="new-password" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">{{ admin_lang('Confirm New Password') }} : <span class="red">*</span></label>
                    <input type="password" class="form-control form-control-lg" name="new-password_confirmation" required>
                </div>
            </div>
        </div>
    </form>
@endsection
