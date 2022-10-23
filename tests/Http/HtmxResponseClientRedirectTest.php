<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Tests\Http;

use Illuminate\Support\Facades\Route;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Mauricius\LaravelHtmx\Tests\TestCase;

class HtmxResponseClientRedirectTest extends TestCase
{
    /** @test */
    public function the_response_should_issue_a_hard_client_side_redirect_by_setting_the_hx_redirect_header()
    {
        Route::get('test', fn () => new HtmxResponseClientRedirect('http://foobar'));

        $this
            ->get('test')
            ->assertOk()
            ->assertHeader('HX-Redirect', 'http://foobar');
    }
}
