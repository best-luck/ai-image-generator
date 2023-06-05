<?php

namespace Vironeer\Plugins\App\Providers;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PluginsServiceProvider extends ServiceProvider
{
    /**
     * The path to the "controllers".
     *
     * This is used by package routes to make the old routes working.
     *
     * @var string
     */
    protected $namespace = 'Vironeer\Plugins\App\Http\Controllers';
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Kernel $kernel)
    {
        Route::group(['namespace' => $this->namespace, 'middleware' => 'web'], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'plugins');
    }
}
