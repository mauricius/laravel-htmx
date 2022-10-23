<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Tests\Http;

use Illuminate\Support\Facades\Route;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRefresh;
use Mauricius\LaravelHtmx\Tests\TestCase;

class HtmxResponseClientRefreshTest extends TestCase
{
    /** @test */
    public function the_response_should_issue_a_hard_refresh_of_the_page_by_setting_the_hx_refresh_header()
    {
        Route::get('test', fn () => new HtmxResponseClientRefresh('http://foobar'));

        $this
            ->get('test')
            ->assertOk()
            ->assertHeader('HX-Refresh', 'true');
    }
}
