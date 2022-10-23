<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse fragment(string $view, string $fragment, array $data = [])
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse addFragment(string $view, string $fragment, array $data = [])
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse addRawFragment(string $rendered)
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse location(string $url)
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse pushUrl(string $url)
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse redirect(string $url)
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse refresh()
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse replaceUrl(string $url)
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse retarget(string $selector)
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse addTrigger(string $event)
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse addTriggerAfterSettle(string $event)
 * @method static \Mauricius\LaravelHtmx\Http\HtmxResponse addTriggerAfterSwap(string $event)
 *
 * @see \Mauricius\LaravelHtmx\Http\HtmxResponse
 */
class HtmxResponse extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mauricius\LaravelHtmx\Http\HtmxResponse::class;
    }
}
