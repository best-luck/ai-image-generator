@extends('backend.layouts.form')
@section('title', admin_lang('Create new tax'))
@section('section', admin_lang('Settings'))
@section('back', route('admin.settings.taxes.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.taxes.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row g-3 mb-2">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Country') }} : <span class="red">*</span></label>
                        <select name="country_id" class="form-select select2" required>
                            <option></option>
                            <option value="0">{{ admin_lang('All countries') }}</option>
                            @foreach (countries() as $country)
                                <option value="{{ $country->id }}" @if (old('country') == $country->id) selected @endif>
                                    {{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Tax percentage') }} : <span class="red">*</span></label>
                        <div class="input-group">
                            <input type="number" name="percentage" class="form-control" placeholder="0" required>
                            <span class="input-group-text"><i class="fas fa-percent"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
