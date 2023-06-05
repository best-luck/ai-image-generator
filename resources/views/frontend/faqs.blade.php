@extends('frontend.layouts.single')
@section('title', lang('FAQs', 'pages'))
@section('content')
    {!! ads_other_pages_top() !!}
    <div class="section-header mb-5">
        <h1 class="mb-3">{{ lang('FAQs', 'pages') }}</h1>
        <p class="fw-light text-muted col-lg-7 mb-0">{{ lang('faqs description', 'pages') }}</p>
    </div>
    <div class="section-body">
        <div class="faqs aos-init aos-animate">
            <div class="accordion-custom">
                <div class="accordion" id="accordion">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ hashid($faq->id) }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ hashid($faq->id) }}" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    {{ $faq->title }}
                                </button>
                            </h2>
                            <div id="collapse{{ hashid($faq->id) }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ hashid($faq->id) }}" data-bs-parent="#accordion">
                                <div class="accordion-body">
                                    <div class="mb-0">{!! $faq->content !!}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            {{ $faqs->links() }}
        </div>
    </div>
    {!! ads_other_pages_bottom() !!}
@endsection
