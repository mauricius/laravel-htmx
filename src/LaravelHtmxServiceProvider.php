<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\View\BladeFragment;

class LaravelHtmxServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->callAfterResolving('blade.compiler', static function (BladeCompiler $blade) {
            $blade->directive('endfragment', static fn () => '');
            $blade->directive('fragment', static fn () => '');
        });

        $this->app->bind(HtmxRequest::class, fn ($container) => HtmxRequest::createFrom($container['request']));

        View::macro('renderFragment', function (string $view, string $fragment, array $data = []) {
            return BladeFragment::render($view, $fragment, $data);
        });
    }
}
