@extends('backend.layouts.form')
@section('title', $navbarMenu->name)
@section('container', 'container-max-lg')
@section('back', route('admin.navbarMenu.index'))
@section('content')
    <div class="card custom-card mb-3">
        <div class="card-body">
            <form id="vironeer-submited-form" action="{{ route('admin.navbarMenu.update', $navbarMenu->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Language') }} :<span class="red">*</span></label>
                    <select name="lang" class="form-select select2" required>
                        <option value="" selected disabled>{{ admin_lang('Choose') }}</option>
                        @foreach ($adminLanguages as $adminLanguage)
                            <option value="{{ $adminLanguage->code }}" @if ($navbarMenu->lang == $adminLanguage->code) selected @endif>
                                {{ $adminLanguage->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ admin_lang('Name') }} : <span class="red">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $navbarMenu->name }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">{{ admin_lang('Link') }} : <span class="red">*</span></label>
                    <input type="link" name="link" class="form-control" placeholder="/"
                        value="{{ $navbarMenu->link }}" required>
                </div>
            </form>
        </div>
    </div>
    <div class="note note-warning">
        <h5>{{ admin_lang('Ready pages') }}</h5>
        @if ($settings->actions->features_page)
            <li class="mb-1"><strong>{{ admin_lang('Features') }}</strong> : <a href="{{ route('features') }}"
                    target="_blank">{{ route('features') }}</a>
            </li>
        @endif
        <li class="mb-1"><strong>{{ admin_lang('Pricing') }}</strong> : <a href="{{ route('pricing') }}"
                target="_blank">{{ route('pricing') }}</a>
        </li>
        @if ($settings->actions->faqs_status)
            <li class="mb-1"><strong>{{ admin_lang('FAQs') }}</strong> : <a href="{{ route('faqs') }}"
                    target="_blank">{{ route('faqs') }}</a>
            </li>
        @endif
        @if ($settings->actions->blog_status)
            <li class="mb-1"><strong>{{ admin_lang('Blog') }}</strong> : <a href="{{ route('blog.index') }}"
                    target="_blank">{{ route('blog.index') }}</a>
            </li>
        @endif
        @if ($settings->actions->contact_page)
            <li><strong>{{ admin_lang('Contact') }}</strong> : <a href="{{ route('contact') }}"
                    target="_blank">{{ route('contact') }}</a>
            </li>
        @endif
    </div>
@endsection
