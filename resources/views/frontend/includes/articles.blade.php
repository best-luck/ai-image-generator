@if ($settings->actions->blog_status && $blogArticles->count() > 0)
    <section id="blogArticles" class="section">
        <div class="container">
            <div class="section-inner">
                <div class="section-header">
                    <h2 class="section-title">{{ lang('Latest blog posts', 'home page') }}</h2>
                    <p class="fw-light text-muted col-lg-7 mb-0">
                        {{ lang('blog section description', 'home page') }}
                    </p>
                </div>
                <div class="section-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 justify-content-center g-3">
                        @foreach ($blogArticles as $blogArticle)
                            <div class="col" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                                <div class="blog-post">
                                    <div class="blog-post-header">
                                        <a href="{{ route('blog.article', $blogArticle->slug) }}">
                                            <img src="{{ asset($blogArticle->image) }}" alt="{{ $blogArticle->title }}"
                                                class="blog-post-img">
                                        </a>
                                    </div>
                                    <div class="blog-post-body">
                                        <div class="blog-post-time">
                                            <time>{{ dateFormat($blogArticle->created_at) }}</time>
                                        </div>
                                        <a href="{{ route('blog.article', $blogArticle->slug) }}"
                                            class="blog-post-title">
                                            <h4>{{ $blogArticle->title }}</h4>
                                        </a>
                                        <p class="blog-post-text">{{ $blogArticle->short_description }}</p>
                                        <div class="mt-2">
                                            <a href="{{ route('blog.article', $blogArticle->slug) }}"
                                                class="link link-secondary">
                                                {{ lang('Read More', 'home page') }} <i
                                                    class="fa fa-arrow-right fa-sm ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        <a href="{{ route('blog.index') }}"
                            class="btn btn-primary-icon btn-md">{{ lang('View More', 'home page') }}
                            <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
