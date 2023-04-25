<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Tests;

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use RuntimeException;
use Spatie\Snapshots\MatchesSnapshots;

class HtmxResponseMacroTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function the_response_should_return_view_content_without_validator()
    {
		$label = "htmx";
		$renderedView = view('simple', [
			'errors' => new ViewErrorBag,
			'label' => $label,
		])->render();

        Route::get('test', fn () => response()->htmx('simple', ['label' => $label]))->middleware([
			ShareErrorsFromSession::class,
			StartSession::class,
		]);

        $response = $this->get('test');

        $response->assertOk();

        $this->assertMatchesSnapshot($renderedView);
    }

	/** @test */
    public function the_response_should_return_view_content_with_validator()
    {
		$validator = validator([
			'email' => 'john.doe'
		], [
			'email' => 'required|email'
		]);

		$label = "htmx";
		$renderedView = view('simple', [
			'errors' => (new ViewErrorBag)->put('default', $validator->errors()),
			'label' => $label,
		])->render();

		Route::get('test', function () use ($label, $validator) {
			response()->htmx('simple', ['label' => $label], $validator);
		})->middleware(StartSession::class);

        $response = $this->get('test');

        $response->assertOk();

        $this->assertMatchesSnapshot($renderedView);
    }
}
