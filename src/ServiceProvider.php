<?php

namespace Knowfox\Entangle;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Knowfox\Entangle\Commands\ImportEntangle;
use Knowfox\Entangle\Models\EventExtension;
use Knowfox\Entangle\Models\LocationExtension;

class ServiceProvider extends IlluminateServiceProvider
{
    protected function mergeConfigRecursiveFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);
        $this->app['config']->set($key, array_merge_recursive(require $path, $config));
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->mergeConfigRecursiveFrom(
            __DIR__ . '/../config.php', 'knowfox'
        );
        $this->loadViewsFrom(__DIR__ . '/../views', 'entangle');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportEntangle::class,
            ]);
        }
    }

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}