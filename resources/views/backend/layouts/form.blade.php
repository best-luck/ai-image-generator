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
        <div class="container @yield('container')">
            <div class="vironeer-page-body px-1 px-sm-2 px-xxl-0">
                <div class="py-4 g-4">
                    <div class="row align-items-center">
                        <div class="col">
                            @include('backend.includes.breadcrumb')
                        </div>
                        <div class="col-auto">
                            @hasSection('language')
                                <div class="dropdown d-inline me-2">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-globe me-2"></i>{{ $active }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        @foreach ($adminLanguages as $adminLanguage)
                                            <li><a class="dropdown-item @if ($adminLanguage->name == $active) active @endif"
                                                    href="?lang={{ $adminLanguage->code }}">{{ $adminLanguage->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @hasSection('back')
                                <a href="@yield('back')" class="btn btn-secondary me-2"><i
                                        class="fas fa-arrow-left me-2"></i>{{ admin_lang('Back') }}</a>
                            @endif
                            @hasSection('link')
                                <a href="@yield('link')" class="btn btn-dark me-2"><i class="fa fa-plus"></i></a>
                            @endif
                            @hasSection('modal')
                                <button type="button" class="btn btn-dark me-2" data-bs-toggle="modal"
                                    data-bs-target="#viewModal">
                                    @yield('modal')
                                </button>
                            @endif
                            <button form="vironeer-submited-form" class="btn btn-primary @yield('btn_action')"
                                @yield('btn_action')>{{ admin_lang('Save') }}</button>
                        </div>
                    </div>
                </div>
                <div class="vironeer-form-page">
                    @yield('content')
                </div>
            </div>
        </div>
        @include('backend.includes.footer')
    </div>
    @include('backend.includes.scripts')
</body>

</html>
