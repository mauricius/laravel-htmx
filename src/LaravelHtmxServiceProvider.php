<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\Validator;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\View\BladeFragment;

class LaravelHtmxServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

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

		Response::macro('htmx', function(string $view, array $viewData = [], Validator $validator = null) {
			if (isset($validator)) {
				// flash current input for the current runtime request
				request()->flash();
				session()->ageFlashData();

				// re-share errors variable with the new validation errors
				View::share('errors', (new ViewErrorBag)->put('default', $validator->errors()));
			}

			return view($view, $viewData);
		});
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-htmx.php' => config_path('laravel-htmx.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-htmx.php',
            'laravel-htmx'
        );
    }
}
