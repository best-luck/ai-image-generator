@if ($settings->actions->faqs_status && $faqs->count() > 0)
    <section id="faqs" class="section" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <div class="section-inner">
                <div class="section-header">
                    <h2 class="section-title">{{ lang('FAQs', 'home page') }}
                    </h2>
                    <p class="fw-light text-muted col-lg-7 mb-0">
                        {{ lang('faqs description', 'home page') }}</p>
                </div>
                <div class="section-body">
                    <div class="faqs">
                        <div class="accordion-custom">
                            <div class="accordion" id="accordion">
                                @foreach ($faqs as $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ hashid($faq->id) }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ hashid($faq->id) }}" aria-expanded="false"
                                                aria-controls="flush-collapseOne">
                                                {{ $faq->title }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ hashid($faq->id) }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ hashid($faq->id) }}" data-bs-parent="#accordion">
                                            <div class="accordion-body">
                                                <div class="mb-0">
                                                    {!! $faq->content !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        <a href="{{ route('faqs') }}"
                            class="btn btn-primary-icon btn-md">{{ lang('View More', 'home page') }}
                            <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
