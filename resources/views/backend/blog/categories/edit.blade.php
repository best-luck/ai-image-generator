@extends('backend.layouts.form')
@section('section', admin_lang('Blog'))
@section('title', $category->name)
@section('container', 'container-max-lg')
@section('back', route('categories.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card p-2 mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Language') }} :<span class="red">*</span></label>
                            <select name="lang" class="form-select select2" required>
                                <option></option>
                                @foreach ($adminLanguages as $adminLanguage)
                                    <option value="{{ $adminLanguage->code }}"
                                        @if ($category->lang == $adminLanguage->code) selected @endif>
                                        {{ $adminLanguage->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Category name') }} : <span
                                    class="red">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}"
                                required />
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Slug') }} : <span class="red">*</span></label>
                    <div class="input-group vironeer-input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ url('blog/categories/') }}/</span>
                        </div>
                        <input type="text" name="slug" class="form-control" value="{{ $category->slug }}" required />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
