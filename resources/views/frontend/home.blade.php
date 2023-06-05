@extends('frontend.layouts.front')
@section('title', $SeoConfiguration->title ?? '')
@section('content')
    {!! ads_home_page_top() !!}
    <header class="header">
        <div class="wrapper">
            <div class="container">
                <div class="wrapper-content">
                    <div class="wrapper-container">
                        <h1 class="title mb-3">{{ lang('AI Image Generator', 'home page') }}</h1>
                        <p class="mb-0 text-muted">
                            {{ lang('Create stunning and unique images with ease using our AI image generation.', 'home page') }}
                        </p>
                        <form id="generator" action="{{ route('images.generator') }}" method="POST">
                            @csrf
                            <div class="card-v mt-5">
                                <div class="generator-search v2">
                                    <div class="row g-3">
                                        @foreach ($image_settings as $setting)
                                            @if ($setting->visibility)
                                                <div class="{{$setting->details=='Enter the details of the tattoo design'?'col-12':'col-lg-4'}} text-start">
                                                    @if ($setting->type=="text-field")
                                                        <label>{{$setting->details}}</label>
                                                        <input type="text" name="{{$setting->details}}" class="form-control"
                                                            placeholder="{{ lang($setting->placeholder) }}"
                                                            value="{{ request('prompt') ?? '' }}" />
                                                    @else
                                                        <?php
                                                            $description = $setting->getDescriptionArray();
                                                        ?>
                                                        <label>{{$setting->details}}</label>
                                                        <select name="{{$setting->details}}" class="form-select w-100">
                                                            <option value="" selected disabled>
                                                                {{ $setting->placeholder }}
                                                            </option>
                                                            @foreach($setting->getCategoriesArray() as $key => $option)
                                                                <option value="{{ $option }}">{{ $description[$key]??$option }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <div class="d-flex justify-content-end mt-3">
                                        <div class="col-lg-3">
                                            <button id="generate-button"
                                                class="btn btn-primary btn-md px-4 w-100"><i
                                                    class="fa-solid fa-rotate me-2"></i>{{ lang('Generate', 'home page') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="processing d-none mt-5">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                            <h5 class="mb-0">{{ lang('Generating...', 'home page') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    {!! ads_home_page_center() !!}
    @if ($generatedImages->count() > 0)
        <div class="section pt-0">
            <div class="container">
                <div class="section-inner">
                    <div class="section-body">
                        <div id="generated-images"
                            class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 g-3 d-none">
                        </div>
                        <div id="default-images"
                            class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 g-3">
                            @foreach ($generatedImages as $generatedImage)
                                <div class="col" data-aos="zoom-in" data-aos-duration="1000">
                                    <div class="ai-image">
                                        <img class="lazy"
                                            data-src="{{ route('images.secure', [hashid($generatedImage->id), $generatedImage->filename]) }}"
                                            alt="{{ $generatedImage->prompt }}" />
                                        <div class="spinner-border"></div>
                                        <div class="ai-image-hover justify-content-end">
                                            <div class="row g-2 alig-items-center">
                                                <div class="col">
                                                    <a href="{{ route('images.show', hashid($generatedImage->id)) }}"
                                                        target="_blank"
                                                        class="btn btn-primary btn-md w-100">{{ lang('View Image') }}</a>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="{{ route('images.download', [hashid($generatedImage->id), $generatedImage->filename]) }}"
                                                        class="btn btn-light btn-md px-3"><i
                                                            class="fas fa-download"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="viewAllImagesButton" class="d-flex justify-content-center mt-5">
                            <a href="{{ route('images.index') }}"
                                class="btn btn-primary-icon btn-lg">{{ lang('View All Generated Images', 'home page') }}
                                <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @include('frontend.includes.faqs')
    @include('frontend.includes.articles')
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/aos/aos.min.css') }}">
    @endpush
    {!! ads_home_page_bottom() !!}
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/aos/aos.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.lazy.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
