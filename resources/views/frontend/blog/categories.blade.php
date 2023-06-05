@extends('frontend.layouts.single')
@section('title', lang('Blog categories', 'blog'))
@section('content')
    {!! ads_blog_page_top() !!}
    <div class="section-body">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card-v">
                    <h5 class="card-v-title mb-4">{{ lang('Categories', 'blog') }}</h5>
                    <div class="categories">
                        @forelse ($blogCategories as $blogCategory)
                            <a href="{{ route('blog.category', $blogCategory->slug) }}" class="category link link-primary">
                                <span class="category-title">{{ $blogCategory->name }}</span>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        @empty
                            <span class="text-muted">{{ lang('No categories found', 'blog') }}</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! ads_blog_page_bottom() !!}
@endsection
