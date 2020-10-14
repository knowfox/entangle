<?php

namespace Knowfox\Entangle;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends IlluminateServiceProvider
{
    protected $namespace = '\Knowfox\Entangle\Controllers';

    protected function mergeConfigRecursiveFrom($path, $key)
    {
        if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $config = $this->app->make('config');

            $config->set($key, array_merge_recursive(
                require $path, $config->get($key, [])
            ));
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigRecursiveFrom(
            __DIR__ . '/../config.php', 'knowfox'
        );

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes.php');

        $this->loadViewsFrom(__DIR__ . '/../views', 'entangle');
    }
}
