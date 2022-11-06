<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\View\BladeFragment;

class LaravelHtmxServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-htmx.php' => config_path('laravel-htmx.php'),
        ], 'config');

        $this->app['blade.compiler']->directive('fragment', function () {
            return '';
        });

        $this->app['blade.compiler']->directive('endfragment', function () {
            return '';
        });

        $this->app->bind(HtmxRequest::class, fn ($container) => HtmxRequest::createFrom($container['request']));

        View::macro('renderFragment', function ($view, $fragment, array $data = []) {
            return BladeFragment::render($view, $fragment, $data);
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-htmx.php',
            'laravel-htmx'
        );
    }
}
