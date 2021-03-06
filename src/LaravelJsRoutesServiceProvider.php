<?php

namespace Macellan\LaravelJsRoutes;

use Illuminate\Support\ServiceProvider;

class LaravelJsRoutesServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\RoutesJavascriptCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('routes.javascript', function ($app) {
            $generator = new Generators\RoutesJavascriptGenerator($app['files'], $app['router']);
            return new Commands\RoutesJavascriptCommand($generator);
        });
    }
}
