<!DOCTYPE html>
<html lang="{{ getLang() }}">

<head>
    @include('frontend.global.head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset(mix('assets/auth/css/app.min.css')) }}">
    @endpush
    @include('frontend.global.styles')
    {!! head_code() !!}
</head>

<body>
    <div class="sign">
        <nav class="sign-nav">
            <div class="container h-100">
                <div class="row row-cols-auto justify-content-between align-items-center flex-nowrap h-100">
                    <div class="col">
                        <a class="logo" href="{{ route('home') }}">
                            <img src="{{ asset($settings->media->light_logo) }}"
                                alt="{{ $settings->general->site_name }}" />
                        </a>
                    </div>
                    <div class="col">
                        <div class="row row-cols-auto g-2 align-items-center flex-nowrap">
                            <div class="col">
                                <div class="auth-dropdown lang me-2" data-dropdown>
                                    <div class="auth-dropdown-btn">
                                        <img src="{{ getLangFlag() }}" alt="{{ getLangName() }}" />
                                        <span>{{ getLangName() }}</span>
                                        <i class="fa fa-chevron-down fa-sm"></i>
                                    </div>
                                    <div class="auth-dropdown-menu">
                                        @foreach ($languages as $language)
                                            <a href="{{ langURL($language->code) }}"
                                                class="auth-dropdown-item {{ getLang() == $language->code ? 'active' : '' }}">
                                                <img src="{{ asset($language->flag) }}" alt="{{ $language->name }}" />
                                                <span>{{ $language->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                @auth
                                    <form class="d-inline" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="sign-btn"><i class="fas fa-sign-out-alt"></i><span
                                                class="ms-2 d-none d-sm-inline-block">{{ lang('Logout', 'auth') }}</span></button>
                                    </form>
                                @else
                                    @if (request()->routeIs('register'))
                                        <a href="{{ route('login') }}" class="sign-btn">
                                            <i class="fa fa-sign-in"></i>
                                            <span
                                                class="ms-2 d-none d-sm-inline-block">{{ lang('Sign In', 'auth') }}</span>
                                        </a>
                                    @elseif(request()->routeIs('login'))
                                        @if ($settings->actions->registration_status)
                                            <a href="{{ route('register') }}" class="sign-btn">
                                                <i class="fas fa-user-plus"></i>
                                                <span
                                                    class="ms-2 d-none d-sm-inline-block">{{ lang('Sign Up', 'auth') }}</span>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="sign-btn">
                                            <i class="fa fa-sign-in"></i>
                                            <span
                                                class="ms-2 d-none d-sm-inline-block">{{ lang('Sign In', 'auth') }}</span>
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="sign-body my-auto">
            <div class="container">
                @yield('content')
            </div>
        </div>
        <footer class="sign-footer mt-auto">
            <div class="container">
                <div class="text-center">
                    <p class="text-muted mb-3 mb-md-0">&copy; <span data-year></span>
                        {{ $settings->general->site_name }} - {{ lang('All rights reserved') }}.</p>
                </div>
            </div>
        </footer>
    </div>
    @push('scripts')
        <script src="{{ asset(mix('assets/auth/js/app.min.js')) }}"></script>
    @endpush
    @include('frontend.configurations.config')
    @include('frontend.configurations.widgets')
    @include('frontend.global.scripts')
    {!! google_captcha() !!}
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}')
            @endforeach
        </script>
    @elseif(session('status'))
        <script>
            toastr.success('{{ session('status') }}')
        </script>
    @elseif(session('resent'))
        <script>
            toastr.success('{{ lang('Link has been resend Successfully', 'auth') }}')
        </script>
    @endif
</body>

</html>
