<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Tests\Http;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Mauricius\LaravelHtmx\Http\HtmxResponse;
use Mauricius\LaravelHtmx\Tests\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class HtmxResponseTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function the_response_should_issue_a_soft_client_side_redirect_by_setting_the_hx_location_header()
    {
        Route::get('test', fn () => with(new HtmxResponse())->location('http://foobar'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Location', 'http://foobar');
    }

    /** @test */
    public function the_response_should_push_a_new_url_by_setting_the_hx_push_url_header()
    {
        Route::get('test', fn () => with(new HtmxResponse())->pushUrl('http://foobar'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Push-Url', 'http://foobar');
    }

    /** @test */
    public function the_response_should_replace_the_current_url_by_setting_the_hx_replace_url_header()
    {
        Route::get('test', fn () => with(new HtmxResponse())->replaceUrl('http://foobar'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Replace-Url', 'http://foobar');
    }

    /** @test */
    public function the_response_should_determine_how_the_response_will_be_swapped_by_setting_the_hx_reswap_header()
    {
        Route::get('test', fn () => with(new HtmxResponse())->reswap('innerHTML'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Reswap', 'innerHTML');
    }

    /** @test */
    public function the_response_should_specify_the_target_of_the_content_to_update_by_setting_the_hx_retarget_header()
    {
        Route::get('test', fn () => with(new HtmxResponse())->retarget('.update-me'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Retarget', '.update-me');
    }

    /** @test */
    public function the_response_should_trigger_a_client_side_event_by_setting_the_hx_trigger_header()
    {
        Route::get('test', fn () => with(new HtmxResponse())->addTrigger('htmx:abort'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger', 'htmx:abort');
    }

    /** @test */
    public function the_response_supports_triggering_multiple_events()
    {
        Route::get(
            'test',
            fn () => with(new HtmxResponse())
                ->addTrigger('htmx:abort')
                ->addTrigger('htmx:load')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger', 'htmx:abort,htmx:load');
    }

    /** @test */
    public function adding_the_same_trigger_to_the_response_multiple_times_will_return_the_event_only_once()
    {
        Route::get(
            'test',
            fn () => with(new HtmxResponse())
                ->addTrigger('htmx:abort')
                ->addTrigger('htmx:abort')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger', 'htmx:abort');
    }

    /** @test */
    public function the_hx_trigger_header_should_json_encode_complex_events()
    {
        Route::get('test', fn () => with(new HtmxResponse())
            ->addTrigger('htmx:load')
            ->addTrigger('showMessage', 'Here Is A Message'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger', '{"htmx:load":null,"showMessage":"Here Is A Message"}');
    }

    /** @test */
    public function the_response_should_trigger_a_client_side_event_after_the_settling_step_by_setting_the_hx_trigger_after_settle_header()
    {
        Route::get('test', fn () => with(new HtmxResponse())->addTriggerAfterSettle('htmx:abort'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Settle', 'htmx:abort');
    }

    /** @test */
    public function the_response_supports_triggering_after_settle_multiple_times()
    {
        Route::get(
            'test',
            fn () => with(new HtmxResponse())
            ->addTriggerAfterSettle('htmx:abort')
            ->addTriggerAfterSettle('htmx:load')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Settle', 'htmx:abort,htmx:load');
    }

    /** @test */
    public function adding_the_same_trigger_after_settle_to_the_response_multiple_times_will_return_the_event_only_once()
    {
        Route::get(
            'test',
            fn () => with(new HtmxResponse())
                ->addTriggerAfterSettle('htmx:abort')
                ->addTriggerAfterSettle('htmx:abort')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Settle', 'htmx:abort');
    }

    /** @test */
    public function the_hx_trigger_after_settle_header_should_json_encode_complex_events()
    {
        Route::get('test', fn () => with(new HtmxResponse())
            ->addTriggerAfterSettle('htmx:load')
            ->addTriggerAfterSettle('showMessage', 'Here Is A Message'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Settle', '{"htmx:load":null,"showMessage":"Here Is A Message"}');
    }

    /** @test */
    public function the_response_should_trigger_a_client_side_event_after_the_swap_step_by_setting_the_hx_trigger_after_swap_header()
    {
        Route::get('test', fn () => with(new HtmxResponse())->addTriggerAfterSwap('htmx:abort'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Swap', 'htmx:abort');
    }

    /** @test */
    public function the_response_supports_triggering_after_swap_multiple_times()
    {
        Route::get(
            'test',
            fn () => with(new HtmxResponse())
            ->addTriggerAfterSwap('htmx:abort')
            ->addTriggerAfterSwap('htmx:load')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Swap', 'htmx:abort,htmx:load');
    }

    /** @test */
    public function adding_the_same_trigger_after_swap_to_the_response_multiple_times_will_return_the_event_only_once()
    {
        Route::get(
            'test',
            fn () => with(new HtmxResponse())
                ->addTriggerAfterSwap('htmx:abort')
                ->addTriggerAfterSwap('htmx:abort')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Swap', 'htmx:abort');
    }

    /** @test */
    public function the_hx_trigger_after_swap_header_should_json_encode_complex_events()
    {
        Route::get('test', fn () => with(new HtmxResponse())
            ->addTriggerAfterSwap('htmx:load')
            ->addTriggerAfterSwap('showMessage', 'Here Is A Message'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Swap', '{"htmx:load":null,"showMessage":"Here Is A Message"}');
    }

    /** @test */
    public function the_response_renders_a_single_fragment()
    {
        Route::get('test', function () {
            $message = 'Htmx';

            return with(new HtmxResponse())
                ->renderFragment('basic', 'double', compact('message'));
        });

        $response = $this->get('test');

        $response->assertOk();

        $this->assertMatchesSnapshot($response->getContent());
    }

    /** @test */
    public function the_response_renders_multiple_fragments_for_out_of_band_swaps()
    {
        Route::get('test', function () {
            $message = 'Htmx';

            return with(new HtmxResponse())
                ->addFragment('multiple', 'upper', compact('message'))
                ->addFragment('multiple', 'lower');
        });

        $response = $this->get('test');

        $response->assertOk();

        $this->assertMatchesSnapshot($response->getContent());
    }

    /** @test */
    public function the_response_returns_a_rendered_fragment()
    {
        Route::get('test', function () {
            $message = 'Htmx';

            return with(new HtmxResponse())
                ->addRenderedFragment(Blade::render('<p>Hello from {{ $message}}</p>', compact('message')));
        });

        $response = $this->get('test');

        $response->assertOk();

        $this->assertMatchesSnapshot($response->getContent());
    }
}
