@extends('backend.layouts.form')
@section('title', $title)
@section('section', admin_lang('Settings'))
@section('back', route('admin.settings.taxes.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.taxes.update', $tax->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row g-3 mb-2">
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Country') }} : <span class="red">*</span></label>
                        <select name="country_id" class="form-select select2" required>
                            <option></option>
                            <option value="0" {{ !$tax->country_id ? 'selected' : '' }}>
                                {{ admin_lang('All countries') }}
                            </option>
                            @foreach (countries() as $country)
                                <option value="{{ $country->id }}" @if ($tax->country_id == $country->id) selected @endif>
                                    {{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ admin_lang('Tax percentage') }} : <span class="red">*</span></label>
                        <div class="input-group">
                            <input type="number" name="percentage" class="form-control" placeholder="0"
                                value="{{ $tax->percentage }}" required>
                            <span class="input-group-text"><i class="fas fa-percent"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
