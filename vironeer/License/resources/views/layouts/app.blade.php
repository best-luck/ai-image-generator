<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/vendor/install/img/favicon.ico') }}" type="image/png" />
    <title>{{ admin_lang('Vironeer Installer') }} - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fontawesome/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/install/css/application.min.css') }}" />
</head>
<body>
    <nav class="vironeer-navbar">
        <div class="container">
            <div class="vironeer-navbar-inner">
                <a href="https://vironeer.com" class="vironeer-logo" target="_blank">
                    <img src="{{ asset('assets/vendor/install/img/logo.png') }}" alt="Vironeer" />
                </a>
                <div class="vironeer-navbar-actions">
                    <a href="https://t.me/vironeer" class="btn btn-light btn-md"
                        target="_blank">{{ admin_lang('Get Help') }}</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="vironeer-section mt-auto">
        <div class="container">
            <div class="vironeer-section-inner">
                <div class="vironeer-section-body">
                    @if ($errors->any())
                        <div class="col-xxl-8 mx-auto">
                            <div class="alert alert-danger mb-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="vironeer-steps col-xxl-8 mx-auto">
                        <div class="vironeer-steps-header">
                            <div
                                class="vironeer-steps-item {{ activeStep(['requirements', 'permissions', 'license', 'database', 'import', 'complete']) }}">
                                <div class="vironeer-steps-item-icon">
                                    <i class="fas fa-check"></i>
                                    <div class="vironeer-steps-item-number">1</div>
                                </div>
                                <div class="vironeer-steps-item-text">{{ admin_lang('Requirements') }}</div>
                            </div>
                            <div
                                class="vironeer-steps-item {{ activeStep(['permissions', 'license', 'database', 'import', 'complete']) }}">
                                <div class="vironeer-steps-item-icon">
                                    <i class="fas fa-check"></i>
                                    <div class="vironeer-steps-item-number">2</div>
                                </div>
                                <div class="vironeer-steps-item-text">{{ admin_lang('Permissions') }}</div>
                            </div>
                            <div
                                class="vironeer-steps-item {{ activeStep(['license', 'database', 'import', 'complete']) }}">
                                <div class="vironeer-steps-item-icon">
                                    <i class="fas fa-check"></i>
                                    <div class="vironeer-steps-item-number">3</div>
                                </div>
                                <div class="vironeer-steps-item-text">{{ admin_lang('License') }}</div>
                            </div>
                            <div class="vironeer-steps-item {{ activeStep(['database', 'import', 'complete']) }}">
                                <div class="vironeer-steps-item-icon">
                                    <i class="fas fa-check"></i>
                                    <div class="vironeer-steps-item-number">4</div>
                                </div>
                                <div class="vironeer-steps-item-text">{{ admin_lang('Database') }}</div>
                            </div>
                            <div class="vironeer-steps-item {{ activeStep(['import', 'complete']) }}">
                                <div class="vironeer-steps-item-icon">
                                    <i class="fas fa-check"></i>
                                    <div class="vironeer-steps-item-number">5</div>
                                </div>
                                <div class="vironeer-steps-item-text">{{ admin_lang('Import') }}</div>
                            </div>
                            <div class="vironeer-steps-item {{ activeStep(['complete']) }}">
                                <div class="vironeer-steps-item-icon">
                                    <i class="fas fa-check"></i>
                                    <div class="vironeer-steps-item-number">6</div>
                                </div>
                                <div class="vironeer-steps-item-text">{{ admin_lang('Completed') }}</div>
                            </div>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="vironeer-footer mt-auto">
        <div class="container">
            <div class="row justify-content-sm-between align-items-center g-3">
                <div class="col-12 col-sm-auto">
                    <p class="text-muted text-center mb-0 small">
                        {{ admin_lang('Vironeer') }} © <span data-year></span> -
                        {{ admin_lang('All rights reserved') }}
                    </p>
                </div>
                <div class="col-12 col-sm-auto">
                    <div class="vironeer-footer-links">
                        <a href="https://codecanyon.net/user/vironeer" class="link">{{ admin_lang('Envato') }}</a>
                        <a href="https://twitter.com/vironeer" class="link">{{ admin_lang('Twitter') }}</a>
                        <a href="https://t.me/vironeer" class="link">{{ admin_lang('Get Help') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
    <script>
        "use strict";
        document.querySelectorAll("[data-year]").forEach((el) => {
            el.textContent = new Date().getFullYear();
        });
        $(".remove-spaces").on('input', function() {
            $(this).val($(this).val().replace(/\s/g, ""));
        });
    </script>
</body>
</html>
