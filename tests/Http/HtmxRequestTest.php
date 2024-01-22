<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Tests\Http;

use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class HtmxRequestTest extends TestCase
{
    /** @test */
    public function a_request_is_not_an_htmx_request_if_the_hx_request_header_is_not_set()
    {
        $request = $this->makeRequest('GET', '/');

        $this->assertFalse($request->isHtmxRequest());
    }

    /** @test */
    public function a_request_is_an_htmx_request_if_the_hx_request_header_is_set_and_is_true()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX-REQUEST' => true]);

        $this->assertTrue($request->isHtmxRequest());
    }

    /** @test */
    public function a_request_is_htmx_boosted_if_the_hx_boosted_header_is_set_and_is_true()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX-BOOSTED' => true]);

        $this->assertTrue($request->isBoosted());
    }

    /** @test */
    public function a_request_is_not_htmx_unboosted_if_it_is_an_htmx_request_but_a_boosted_one()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX-REQUEST' => true, 'HTTP_HX-BOOSTED' => true]);

        $this->assertFalse($request->isHtmxButNotBoosted());
    }

    /** @test */
    public function a_request_is_htmx_unboosted_if_it_is_an_htmx_request_but_not_a_boosted_one()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX-REQUEST' => true]);

        $this->assertTrue($request->isHtmxButNotBoosted());
    }

    /** @test */
    public function a_request_should_return_the_current_url_of_the_browser_that_makes_the_request_if_the_hx_current_url_is_set()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX_CURRENT_URL' => 'http://localhost']);

        $this->assertEquals('http://localhost', $request->getCurrentUrl());
    }

    /** @test */
    public function a_request_is_a_history_restore_request_if_the_hx_history_restore_request_header_is_set_and_is_true()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX_HISTORY_RESTORE_REQUEST' => true]);

        $this->assertTrue($request->isHistoryRestoreRequest());
    }

    /** @test */
    public function a_request_should_return_the_prompt_response_if_the_hx_prompt_header_is_set()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX_PROMPT' => 'Yes please']);

        $this->assertEquals('Yes please', $request->getPromptResponse());
    }

    /** @test */
    public function a_request_should_return_the_id_of_the_target_element_if_the_hx_target_header_is_set()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX_TARGET' => 'my-id']);

        $this->assertEquals('my-id', $request->getTarget());
    }

    /** @test */
    public function a_request_should_return_the_name_of_the_triggered_element_if_the_hx_trigger_name_header_is_set()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX_TRIGGER_NAME' => 'my-id']);

        $this->assertEquals('my-id', $request->getTriggerName());
    }

    /** @test */
    public function a_request_should_return_the_id_of_the_triggered_element_if_the_hx_trigger_header_is_set()
    {
        $request = $this->makeRequest('GET', '/', [], [], [], ['HTTP_HX_TRIGGER' => 'my-id']);

        $this->assertEquals('my-id', $request->getTriggerId());
    }

    protected function makeRequest(
        string $method,
        string $uri,
        array $parameters = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ): HtmxRequest {
        $uri = $this->prepareUrlForRequest($uri);

        return HtmxRequest::createFromBase(
            SymfonyRequest::create(
                $uri,
                $method,
                $parameters,
                $cookies,
                $files,
                $server,
                $content
            )
        );
    }
}
