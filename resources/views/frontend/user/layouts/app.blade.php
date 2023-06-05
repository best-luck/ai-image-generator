<!DOCTYPE html>
<html lang="{{ getLang() }}">

<head>
    @include('frontend.global.head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset(mix('assets/main/css/app.min.css')) }}">
    @endpush
    @include('frontend.global.styles')
</head>

<body class="dash">
    @include('frontend.user.includes.navbar')
    <div class="dash-content px-0">
        <div class="container-xxl">
            <div class="row row-cols-auto justify-content-between align-items-center g-2 mb-4">
                <div class="col">
                    @if (!request()->routeIs('checkout.proccess'))
                        <h4 class="mb-2">@yield('title')</h4>
                        @include('frontend.user.includes.breadcrumb')
                    @endif
                </div>
                @hasSection('back')
                    <div class="col">
                        <a href="@yield('back')" class="btn btn-light btn-md px-3"><i
                                class="fas fa-arrow-left me-2"></i>{{ lang('back', 'account') }}</a>
                    </div>
                @endif
                @if (request()->routeIs('user.gallery.index'))
                    <div class="col">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-md"><i
                                class="fa-solid fa-rotate me-2"></i>{{ lang('Generate New Images', 'gallery') }}</a>
                    </div>
                @endif
            </div>
            <div class="dash-content-body">
                @if (
                    !request()->routeIs('checkout.index') &&
                        auth()->user()->isSubscribed())
                    @if (!auth()->user()->subscription->isCancelled())
                        @if (auth()->user()->subscription->isAboutToExpire())
                            <div class="alert alert-warning">
                                <h5 class="mb-3"><i
                                        class="fa-solid fa-triangle-exclamation me-2"></i>{{ lang('Action Required!', 'account') }}
                                </h5>
                                <p class="mb-0">{{ lang('subscription about to expire notice', 'account') }}
                                </p>
                            </div>
                        @elseif(auth()->user()->subscription->isExpired())
                            <div class="alert alert-danger">
                                <h5 class="mb-3"><i
                                        class="fa-solid fa-triangle-exclamation me-2"></i>{{ lang('Action Required!', 'account') }}
                                </h5>
                                <p class="mb-0">{{ lang('subscription expired notice', 'account') }}</p>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-danger">
                            <p class="mb-0"><i
                                    class="fa-solid fa-triangle-exclamation me-2"></i>{{ lang('subscription cancelled notice', 'account') }}
                            </p>
                        </div>
                    @endif
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    @include('frontend.user.includes.footer')
    @push('scripts')
        <script src="{{ asset(mix('assets/main/js/app.min.js')) }}"></script>
    @endpush
    @include('frontend.configurations.config')
    @include('frontend.configurations.widgets')
    @include('frontend.global.scripts')
</body>

</html>
