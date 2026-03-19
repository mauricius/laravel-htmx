<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Tests\Http;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Mauricius\LaravelHtmx\Http\HtmxResponse;
use Mauricius\LaravelHtmx\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Snapshots\MatchesSnapshots;

class HtmxResponseTest extends TestCase
{
    use MatchesSnapshots;

    #[Test]
    public function the_response_should_issue_a_soft_client_side_redirect_by_setting_the_hx_location_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->location('http://foobar'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Location', 'http://foobar');
    }

    #[Test]
    public function the_hx_location_header_should_support_json_notation(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->location([
            'path' => '/test2',
            'target' => '#testdiv'
        ]));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Location', '{"path":"/test2","target":"#testdiv"}');
    }

    #[Test]
    public function the_response_should_push_a_new_url_by_setting_the_hx_push_url_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->pushUrl('http://foobar'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Push-Url', 'http://foobar');
    }

    #[Test]
    public function the_response_should_redirect_by_setting_the_hx_redirect_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->redirect('http://foobar'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Redirect', 'http://foobar');
    }

    #[Test]
    public function the_response_should_refresh_by_setting_the_hx_refresh_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->refresh());

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Refresh', 'true');
    }

    #[Test]
    public function the_response_should_replace_the_current_url_by_setting_the_hx_replace_url_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->replaceUrl('http://foobar'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Replace-Url', 'http://foobar');
    }

    #[Test]
    public function the_response_should_determine_how_the_response_will_be_swapped_by_setting_the_hx_reswap_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->reswap('innerHTML'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Reswap', 'innerHTML');
    }

    #[Test]
    public function the_response_should_specify_the_target_of_the_content_to_update_by_setting_the_hx_retarget_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->retarget('.update-me'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Retarget', '.update-me');
    }

    #[Test]
    public function the_response_should_trigger_a_client_side_event_by_setting_the_hx_trigger_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->addTrigger('htmx:abort'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger', 'htmx:abort');
    }

    #[Test]
    public function the_response_supports_triggering_multiple_events(): void
    {
        Route::get(
            'test',
            fn () => (new HtmxResponse())
                ->addTrigger('htmx:abort')
                ->addTrigger('htmx:load')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger', 'htmx:abort,htmx:load');
    }

    #[Test]
    public function adding_the_same_trigger_to_the_response_multiple_times_will_return_the_event_only_once(): void
    {
        Route::get(
            'test',
            fn () => (new HtmxResponse())
                ->addTrigger('htmx:abort')
                ->addTrigger('htmx:abort')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger', 'htmx:abort');
    }

    #[Test]
    public function the_hx_trigger_header_should_json_encode_complex_events(): void
    {
        Route::get('test', fn () => (new HtmxResponse())
            ->addTrigger('htmx:load')
            ->addTrigger('showMessage', 'Here Is A Message'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger', '{"htmx:load":null,"showMessage":"Here Is A Message"}');
    }

    #[Test]
    public function the_response_should_trigger_a_client_side_event_after_the_settling_step_by_setting_the_hx_trigger_after_settle_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->addTriggerAfterSettle('htmx:abort'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Settle', 'htmx:abort');
    }

    #[Test]
    public function the_response_supports_triggering_after_settle_multiple_times(): void
    {
        Route::get(
            'test',
            fn () => (new HtmxResponse())
            ->addTriggerAfterSettle('htmx:abort')
            ->addTriggerAfterSettle('htmx:load')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Settle', 'htmx:abort,htmx:load');
    }

    #[Test]
    public function adding_the_same_trigger_after_settle_to_the_response_multiple_times_will_return_the_event_only_once(): void
    {
        Route::get(
            'test',
            fn () => (new HtmxResponse())
                ->addTriggerAfterSettle('htmx:abort')
                ->addTriggerAfterSettle('htmx:abort')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Settle', 'htmx:abort');
    }

    #[Test]
    public function the_hx_trigger_after_settle_header_should_json_encode_complex_events(): void
    {
        Route::get('test', fn () => (new HtmxResponse())
            ->addTriggerAfterSettle('htmx:load')
            ->addTriggerAfterSettle('showMessage', 'Here Is A Message'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Settle', '{"htmx:load":null,"showMessage":"Here Is A Message"}');
    }

    #[Test]
    public function the_response_should_trigger_a_client_side_event_after_the_swap_step_by_setting_the_hx_trigger_after_swap_header(): void
    {
        Route::get('test', fn () => (new HtmxResponse())->addTriggerAfterSwap('htmx:abort'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Swap', 'htmx:abort');
    }

    #[Test]
    public function the_response_supports_triggering_after_swap_multiple_times(): void
    {
        Route::get(
            'test',
            fn () => (new HtmxResponse())
            ->addTriggerAfterSwap('htmx:abort')
            ->addTriggerAfterSwap('htmx:load')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Swap', 'htmx:abort,htmx:load');
    }

    #[Test]
    public function adding_the_same_trigger_after_swap_to_the_response_multiple_times_will_return_the_event_only_once(): void
    {
        Route::get(
            'test',
            fn () => (new HtmxResponse())
                ->addTriggerAfterSwap('htmx:abort')
                ->addTriggerAfterSwap('htmx:abort')
        );

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Swap', 'htmx:abort');
    }

    #[Test]
    public function the_hx_trigger_after_swap_header_should_json_encode_complex_events(): void
    {
        Route::get('test', fn () => (new HtmxResponse())
            ->addTriggerAfterSwap('htmx:load')
            ->addTriggerAfterSwap('showMessage', 'Here Is A Message'));

        $response = $this->get('test');

        $response->assertOk();
        $response->assertHeader('HX-Trigger-After-Swap', '{"htmx:load":null,"showMessage":"Here Is A Message"}');
    }

    #[Test]
    public function the_response_renders_a_single_fragment(): void
    {
        Route::get('test', function () {
            $message = 'Htmx';

            return (new HtmxResponse())
                ->renderFragment('basic', 'double', compact('message'));
        });

        $response = $this->get('test');

        $response->assertOk();

        $this->assertMatchesSnapshot($response->getContent());
    }

    #[Test]
    public function the_response_renders_multiple_fragments_for_out_of_band_swaps(): void
    {
        Route::get('test', function () {
            $message = 'Htmx';

            return (new HtmxResponse())
                ->addFragment('multiple', 'upper', compact('message'))
                ->addFragment('multiple', 'lower');
        });

        $response = $this->get('test');

        $response->assertOk();

        $this->assertMatchesSnapshot($response->getContent());
    }

    #[Test]
    public function the_response_returns_a_rendered_fragment(): void
    {
        Route::get('test', function () {
            $message = 'Htmx';

            return (new HtmxResponse())
                ->addRenderedFragment(Blade::render('<p>Hello from {{ $message}}</p>', compact('message')));
        });

        $response = $this->get('test');

        $response->assertOk();

        $this->assertMatchesSnapshot($response->getContent());
    }

    #[Test]
    public function the_response_returns_the_whole_view(): void
    {
        Route::get('test', fn () => new HtmxResponse(View::make('nested')));

        $response = $this->get('test');

        $response->assertOk();

        $this->assertMatchesSnapshot($response->getContent());
    }
}
