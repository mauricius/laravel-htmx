<?php

namespace Mauricius\LaravelHtmx\Tests\Http;

use Illuminate\Support\Facades\Route;
use Mauricius\LaravelHtmx\Http\HtmxResponseStopPolling;
use Mauricius\LaravelHtmx\Tests\TestCase;

class HtmxResponseStopPollingTest extends TestCase
{
    /** @test */
    public function the_response_should_return_a_286_status_code_to_cancel_polling()
    {
        Route::get('test', fn () => new HtmxResponseStopPolling());

        $this
            ->get('test')
            ->assertStatus(HtmxResponseStopPolling::HTMX_STOP_POLLING);
    }
}
