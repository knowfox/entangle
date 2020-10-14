<?php

namespace Knowfox\Entangle;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends IlluminateServiceProvider
{
    protected $namespace = '\Knowfox\Entangle\Controllers';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config.php', 'knowfox'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes.php');

        $this->loadViewsFrom(__DIR__ . '/../views', 'entangle');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('entangle.php'),
        ]);
    }
}
