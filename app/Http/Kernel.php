<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Tests\Illuminate\VerifySessionRequests::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        'vironeer.localize' => \App\Http\Middleware\Localization::class,
        'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
        'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        'admin' => \App\Http\Middleware\RedirectNotAdmin::class,
        'admin.guest' => \App\Http\Middleware\RedirectAdmin::class,
        'ajax.only' => \App\Http\Middleware\AjaxOnlyMiddleware::class,
        'check.registration' => \App\Http\Middleware\RegistrationStatusCheck::class,
        'disable.registration' => \App\Http\Middleware\RegistrationDisable::class,
        'UserStatusCheck' => \App\Http\Middleware\UserStatusCheck::class,
        '2fa.verify' => \App\Http\Middleware\TowFactorVerify::class,
        'demo' => \App\Http\Middleware\DemoMode::class,
        'disable.blog' => \App\Http\Middleware\BlogDisable::class,
        'disable.contact' => \App\Http\Middleware\ContactPageDisable::class,
        'disable.features' => \App\Http\Middleware\FeaturesPageDisable::class,
        'disable.faqs' => \App\Http\Middleware\FaqsDisable::class,
        'subscribed' => \App\Http\Middleware\IsSubscribed::class,
    ];
}