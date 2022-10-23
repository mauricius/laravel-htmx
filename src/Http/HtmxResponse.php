<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Http;

use Illuminate\Http\Response;
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

    public function addTrigger(string $event): static
    {
        $this->triggers[] = $event;

        return $this;
    }

    public function addTriggerAfterSettle(string $event): static
    {
        $this->triggersAfterSettle[] = $event;

        return $this;
    }

    public function addTriggerAfterSwap(string $event): static
    {
        $this->triggersAfterSwap[] = $event;

        return $this;
    }

    public function fragment(string $view, string $fragment, array $data = []): static
    {
        $this->fragments = [BladeFragment::render($view, $fragment, $data)];

        return $this;
    }

    public function addFragment(string $view, string $fragment, array $data = []): static
    {
        $this->fragments[] = BladeFragment::render($view, $fragment, $data);

        return $this;
    }

    public function addRawFragment(string $rendered): static
    {
        $this->fragments[] = $rendered;

        return $this;
    }

    public function prepare(Request $request): static
    {
        $this->appendTriggers();

        return parent::prepare($request);
    }

    public function getContent(): string
    {
        return implode('', $this->fragments);
    }

    private function appendTriggers()
    {
        if (count($this->triggers)) {
            $this->headers->set('HX-Trigger', implode(',', $this->triggers));
        }

        if (count($this->triggersAfterSettle)) {
            $this->headers->set('HX-Trigger-After-Settle', implode(',', $this->triggersAfterSettle));
        }

        if (count($this->triggersAfterSwap)) {
            $this->headers->set('HX-Trigger-After-Swap', implode(',', $this->triggersAfterSwap));
        }
    }
}
