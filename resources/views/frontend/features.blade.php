@extends('frontend.layouts.single')
@section('title', lang('features', 'pages'))
@section('content')
    {!! ads_other_pages_top() !!}
    <div class="section-header mb-5">
        <h1 class="mb-3">{{ lang('Features', 'pages') }}</h1>
        <p class="fw-light text-muted col-lg-7 mb-0">
            {{ lang('Features description', 'pages') }}
        </p>
    </div>
    <div class="section-body">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 justify-content-center g-3">
            @foreach ($features as $feature)
                <div class="col">
                    <div class="feature">
                        <div class="card-v align-items-center text-center">
                            <div class="card-v-icon">
                                <img src="{{ asset($feature->image) }}" alt="{{ $feature->title }}" />
                            </div>
                            <h5 class="card-v-title">{{ $feature->title }}</h5>
                            <p class="card-v-text">{{ $feature->content }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {!! ads_other_pages_bottom() !!}
@endsection
