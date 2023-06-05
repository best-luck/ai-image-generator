@extends('frontend.layouts.single')
@section('title', $blogArticle->title)
@section('description', $blogArticle->short_description)
@section('og_image', asset($blogArticle->image))
@section('content')
    <div class="section-body">
        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="row row-cols-1 g-4">
                    <div class="col">
                        <div class="blog-post v2 p-4">
                            <div class="blog-post-header">
                                <img src="{{ asset($blogArticle->image) }}" alt="{{ $blogArticle->title }}"
                                    class="blog-post-img">
                            </div>
                            <div class="blog-post-body px-0">
                                <span class="blog-post-title text-normal">
                                    <h5>{{ $blogArticle->title }}</h5>
                                </span>
                                <div class="post-meta mb-3">
                                    <div class="post-meta-item">
                                        <i class="far fa-user"></i>
                                        <span>{{ $blogArticle->admin->name }}</span>
                                    </div>
                                    <div class="post-meta-item">
                                        <i class="fa-regular fa-calendar"></i>
                                        <time>{{ dateFormat($blogArticle->created_at) }}</time>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    {!! ads_blog_page_article_top() !!}
                                    {!! $blogArticle->content !!}
                                    {!! ads_blog_page_article_Bottom() !!}
                                </div>
                                <div class="share mt-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                        class="social-btn social-facebook" target="_blank">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?text={{ url()->current() }}"
                                        class="social-btn social-twitter" target="_blank">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}"
                                        class="social-btn social-linkedin" target="_blank">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ url()->current() }}" class="social-btn social-whatsapp"
                                        target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="http://pinterest.com/pin/create/button/?url={{ url()->current() }}"
                                        class="social-btn social-pinterest" target="_blank">
                                        <i class="fab fa-pinterest"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="blog-post-footer px-0">
                                <div class="comments">
                                    <h5 class="comments-title">
                                        <i class="far fa-comments me-2"></i> {{ lang('Comments', 'blog') }}
                                        ({{ $blogArticleComments->count() }})
                                    </h5>
                                    @forelse ($blogArticleComments as $blogArticleComment)
                                        <div class="comment">
                                            <div class="comment-img">
                                                <img src="{{ asset($blogArticleComment->user->avatar) }}"
                                                    alt="{{ $blogArticleComment->user->name }}">
                                            </div>
                                            <div class="comment-info">
                                                <div class="d-flex flex-column">
                                                    <h6 class="comment-title mb-1">
                                                        {{ $blogArticleComment->user->name }}</h6>
                                                    <time
                                                        class="comment-time text-muted mb-2">{{ dateFormat($blogArticleComment->created_at) }}</time>
                                                </div>
                                                <p class="comment-text mb-0 text-muted">{!! allowBr($blogArticleComment->comment) !!}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <span class="text-muted">{{ lang('No comments found', 'blog') }}</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card-v">
                            @auth
                                <h5 class="card-v-title mb-4">{{ lang('Leave a comment', 'blog') }}</h5>
                                <form action="{{ route('blog.article', $blogArticle->slug) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="comment" class="form-control" rows="6" placeholder="{{ lang('Your comment', 'blog') }}" required></textarea>
                                    </div>
                                    {!! display_captcha() !!}
                                    <button class="btn btn-primary btn-md">{{ lang('Publish', 'blog') }}</button>
                                </form>
                            @else
                                <span class="text-muted text-center">
                                    {{ lang('Login or create account to leave comments', 'blog') }}
                                </span>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @include('frontend.blog.includes.sidebar')
        </div>
    </div>
    @push('scripts')
        {!! google_captcha() !!}
    @endpush
@endsection
