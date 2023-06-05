<?php

namespace Vironeer\License\App\Providers;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Vironeer\License\App\Http\Middleware\InstalledMiddleware;
use Vironeer\License\App\Http\Middleware\NotInstalledMiddleware;

class VironeerLicenseServiceProvider extends ServiceProvider
{
    /**
     * The path to the "controllers".
     *
     * This is used by package routes to make the old routes working.
     *
     * @var string
     */
    protected $namespace = 'Vironeer\License\App\Http\Controllers';
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Kernel $kernel)
    {
        $this->registerHelpers();

        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('installed', InstalledMiddleware::class);

        $router->aliasMiddleware('notInstalled', NotInstalledMiddleware::class);

        Route::group(['namespace' => $this->namespace], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'vironeer');
    }
    /**
     * Register helpers file
     */
    public function registerHelpers()
    {
        if (file_exists($file = __DIR__ . '/../Http/Helpers/Helper.php')) {
            require $file;
        }
    }
}
