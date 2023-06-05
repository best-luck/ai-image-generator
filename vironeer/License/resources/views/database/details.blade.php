@extends('vironeer::layouts.app')
@section('title', admin_lang('Database'))
@section('content')
    <div class="vironeer-steps-body">
        <p class="vironeer-form-info-text">
            {{ admin_lang('Enter your database details. You can read the docs included with the script files to learn how to create the database, please do not use the hashtag "#" or spaces on the database details.') }}
        </p>
        <form action="{{ route('install.database.details') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ admin_lang('Database host') }} : <span class="red">*</span></label>
                <div class="input-group">
                    <input type="text" name="db_host" class="form-control form-control-md remove-spaces"
                        placeholder="{{ admin_lang('Enter database host') }}" value="{{ old('db_host') ?? 'localhost' }}" required>
                    <span class="input-group-text"><i class="fas fa-server"></i></span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ admin_lang('Database name') }} : <span class="red">*</span></label>
                <div class="input-group">
                    <input type="text" name="db_name" class="form-control form-control-md remove-spaces"
                        placeholder="{{ admin_lang('Enter database name') }}" value="{{ old('db_name') }}" autocomplete="off"
                        required>
                    <span class="input-group-text"><i class="fas fa-question-circle"></i></span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ admin_lang('Database username') }} : <span class="red">*</span></label>
                <div class="input-group">
                    <input type="text" name="db_user" class="form-control form-control-md remove-spaces"
                        placeholder="{{ admin_lang('Enter database username') }}" value="{{ old('db_user') }}" autocomplete="off"
                        required>
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">{{ admin_lang('Database password') }} : </label>
                <div class="input-group">
                    <input type="password" name="db_pass" class="form-control form-control-md remove-spaces"
                        placeholder="{{ admin_lang('Enter database password') }}" autocomplete="off">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
            </div>
            <button class="btn btn-primary btn-md">{{ admin_lang('Continue') }}<i class="fas fa-arrow-right ms-2"></i></button>
        </form>
    </div>
@endsection
