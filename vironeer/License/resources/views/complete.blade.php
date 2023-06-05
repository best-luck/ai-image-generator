@extends('vironeer::layouts.app')
@section('title', admin_lang('Complete'))
@section('content')
    <div class="vironeer-steps-body">
        <p class="vironeer-form-info-text">
            {{ admin_lang('Enter your website and admin access details, make sure you remember the admin access path.') }}
        </p>
        <form id="completeForm" action="{{ route('install.complete') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ admin_lang('Website name') }} : <span class="red">*</span></label>
                <div class="input-group">
                    <input type="text" name="website_name" value="{{ old('website_name') }}"
                        class="form-control form-control-md" placeholder="{{ admin_lang('Website name') }}" autocomplete="off"
                        required>
                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ admin_lang('Website URL') }} : <span class="red">*</span></label>
                <div class="input-group">
                    <input type="text" name="website_url" value="{{ old('website_url') ?? url('/') }}"
                        class="form-control form-control-md remove-spaces" placeholder="{{ admin_lang('Website URL') }}" required>
                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ admin_lang('Admin panel access path') }} :
                    <span class="red">*</span>
                    <small class="text-muted">({{ admin_lang('Letters and numbers only') }})</small>
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-globe me-2"></i>{{ url('/') }}/</span>
                    <input id="adminPath" type="text" name="admin_path" value="{{ old('admin_path') ?? 'admin' }}"
                        class="form-control form-control-md remove-spaces" placeholder="{{ admin_lang('admin') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ admin_lang('Admin email') }} : <span class="red">*</span></label>
                <div class="input-group rtl">
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-md"
                        placeholder="john@example.com" autocomplete="off" required>
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ admin_lang('Admin password') }} : <span class="red">*</span></label>
                <div class="input-group rtl">
                    <input type="password" name="password" class="form-control form-control-md"
                        placeholder="{{ admin_lang('Password') }}" autocomplete="off" required>
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">{{ admin_lang('Confirm admin password') }} : <span class="red">*</span></label>
                <div class="input-group rtl">
                    <input type="password" name="password_confirmation" class="form-control form-control-md"
                        placeholder="{{ admin_lang('Confirm password') }}" autocomplete="off" required>
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-between align-items-center">
            <form action="{{ route('install.complete.back') }}" method="POST">
                @csrf
                <button class="btn btn-dark btn-md"><i class="fas fa-arrow-left me-2"></i>{{ admin_lang('Back') }}</button>
            </form>
            <button form="completeForm" class="btn btn-primary btn-md">{{ admin_lang('Finish') }}<i
                    class="fas fa-arrow-right ms-2"></i></button>
        </div>
    </div>
    @push('scripts')
        <script>
            $("#adminPath").on('input', function() {
                $(this).val($(this).val().replace(/[^a-zA-Z0-9 _]/g, ""));
            });
        </script>
    @endpush
@endsection
