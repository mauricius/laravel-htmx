<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Http;

use Illuminate\Http\Response;
use Mauricius\LaravelHtmx\Utils;
use Mauricius\LaravelHtmx\View\BladeFragment;
use Symfony\Component\HttpFoundation\Request;

class HtmxResponse extends Response
{
    private array $fragments = [];

    private array $triggers = [];

    private array $triggersAfterSettle = [];

    private array $triggersAfterSwap = [];

    public function location(string $url): static
    {
        $this->headers->set('HX-Location', $url);

        return $this;
    }

    public function pushUrl(string $url): static
    {
        $this->headers->set('HX-Push-Url', $url);

        return $this;
    }

    public function replaceUrl(string $url): static
    {
        $this->headers->set('HX-Replace-Url', $url);

        return $this;
    }

    public function reswap(string $option): static
    {
        $this->headers->set('HX-Reswap', $option);

        return $this;
    }

    public function retarget(string $selector): static
    {
        $this->headers->set('HX-Retarget', $selector);

        return $this;
    }

    public function addTrigger(string $key, string|array|null $body = null): static
    {
        $this->triggers[$key] = $body;

        return $this;
    }

    public function addTriggerAfterSettle(string $key, string|array|null $body = null): static
    {
        $this->triggersAfterSettle[$key] = $body;

        return $this;
    }

    public function addTriggerAfterSwap(string $key, string|array|null $body = null): static
    {
        $this->triggersAfterSwap[$key] = $body;

        return $this;
    }

    public function renderFragment(string $view, string $fragment, array $data = []): static
    {
        $this->fragments = [BladeFragment::render($view, $fragment, $data)];

        return $this;
    }

    public function addFragment(string $view, string $fragment, array $data = []): static
    {
        $this->fragments[] = BladeFragment::render($view, $fragment, $data);

        return $this;
    }

    public function addRenderedFragment(string $rendered): static
    {
        $this->fragments[] = $rendered;

        return $this;
    }

    public function prepare(Request $request): static
    {
        $this->appendTriggers();
        $this->setContent($this->getContent());

        return parent::prepare($request);
    }

    public function getContent(): string
    {
        if (count($this->fragments) > 0) {
            return implode('', $this->fragments);
        }

        return parent::getContent();
    }

    private function appendTriggers(): void
    {
        if (count($this->triggers)) {
            $this->headers->set('HX-Trigger', $this->encodeTriggers($this->triggers));
        }

        if (count($this->triggersAfterSettle)) {
            $this->headers->set('HX-Trigger-After-Settle', $this->encodeTriggers($this->triggersAfterSettle));
        }

        if (count($this->triggersAfterSwap)) {
            $this->headers->set('HX-Trigger-After-Swap', $this->encodeTriggers($this->triggersAfterSwap));
        }
    }

    private function encodeTriggers(array $triggers): string
    {
        if (Utils::containsANonNullableElement($triggers)) {
            return json_encode($triggers);
        }

        return implode(',', array_keys($triggers));
    }
}
