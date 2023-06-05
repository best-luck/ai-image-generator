<!DOCTYPE html>
<html lang="{{ getLang() }}">

<head>
    @include('frontend.global.head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset(mix('assets/main/css/app.min.css')) }}">
    @endpush
    @include('frontend.global.styles')
    {!! head_code() !!}
</head>

<body>
    @include('frontend.includes.navbar')
    <div class="section">
        <div class="container">
            <div class="section-inner" data-aos="fade-zoom-in" data-aos-duration="2000">
                @yield('content')
            </div>
        </div>
    </div>
    @include('frontend.includes.footer')
    @push('scripts')
        <script src="{{ asset(mix('assets/main/js/app.min.js')) }}"></script>
    @endpush
    @include('frontend.configurations.config')
    @include('frontend.configurations.widgets')
    @include('frontend.global.scripts')
</body>

</html>
