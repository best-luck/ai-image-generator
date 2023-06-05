@extends('frontend.layouts.single')
@section('title', $page->title)
@section('description', $page->short_description)
@section('content')
    {!! ads_other_pages_top() !!}
    <div class="section-header mb-5">
        <h1 class="mb-3">{{ $page->title }}</h1>
    </div>
    <div class="section-body">
        <div class="card-v page-content">
            {!! $page->content !!}
        </div>
    </div>
    {!! ads_other_pages_bottom() !!}
@endsection
