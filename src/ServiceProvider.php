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
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        Config::set('knowfox.types',
            array_unique(
                array_merge(
                    config('knowfox.types'),
                    ['event', 'location', 'timeline']
                )
            )
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