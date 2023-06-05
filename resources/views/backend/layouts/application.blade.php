<!DOCTYPE html>
<html lang="{{ getLang() }}">

<head>
    @include('backend.includes.head')
    @include('backend.includes.styles')
</head>

<body>
    @include('backend.includes.sidebar')
    <div class="vironeer-page-content">
        @include('backend.includes.header')
        <div class="container">
            <div class="vironeer-page-body">
                <div class="py-4 g-4">
                    <div class="row align-items-center">
                        <div class="col">
                            @include('backend.includes.breadcrumb')
                        </div>
                        <div class="col-auto">
                            @hasSection('back')
                                <a href="@yield('back')" class="btn btn-secondary"><i
                                        class="fas fa-arrow-left me-2"></i>{{ admin_lang('Back') }}</a>
                            @endif
                            @hasSection('access')
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        @yield('access')
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item"
                                                href="{{ route('admin.settings.general') }}">{{ admin_lang('General Settings') }}</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('admin.settings.pages.index') }}">{{ admin_lang('Website Pages') }}</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('admin.settings.languages.index') }}">{{ admin_lang('Languages') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            @if (request()->routeIs('admin.system.index'))
                                <a href="{{ $system->profile }}" target="_blank" class="btn btn-secondary btn-lg"><i
                                        class="far fa-question-circle me-2"></i>{{ admin_lang('Get Help') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
        @include('backend.includes.footer')
    </div>
    @include('backend.includes.scripts')
</body>

</html>
