<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\View;

class BladeFragmentParser
{
    public function __construct(private string $openDirective, private string $closeDirective)
    {
    }

    /**
     * @param string $content
     * @return CloseFragmentElement[]|OpenFragmentElement[]
     */
    public function parse(string $content): array
    {
        $content = $this->normalizeLineEndings($content);

        return $this->prepareNodeList($content);
    }

    /**
     * @param string $content
     * @return array<OpenFragmentElement|CloseFragmentElement>
     */
    private function prepareNodeList(string $content): array
    {
        $re = sprintf('/(?<!@)@%s[ \t]*\([\'"](.+?)[\'"]\)|@%s/', $this->openDirective, $this->closeDirective);

        preg_match_all($re, $content, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);

        if (! is_array($matches) || count($matches) < 2) {
            return [];
        }

        $lastOffset = 0;

        /** @var array $nodes */
        $nodes = array_map(function (array $match) use ($content, &$lastOffset) {
            // Convert regex offsets to multibyte offsets.
            $offset = $match[0][1];

            if ($offset !== 0) {
                $offset = mb_strpos($content, $match[0][0], $lastOffset + 1);
            }

            if ($offset === false) {
                $offset = $match[0][1];
            }

            $lastOffset = $offset + 1;

            if (str_starts_with($match[0][0], sprintf('@%s', $this->openDirective))) {
                $openElement = new OpenFragmentElement();
                $openElement->name = $match[1][0];
                $openElement->startOffset = $offset;
                $openElement->endOffset = $offset + mb_strlen($match[0][0]);

                return $openElement;
            }

            if (str_starts_with($match[0][0], sprintf('@%s', $this->closeDirective))) {
                $closeElement = new CloseFragmentElement();
                $closeElement->startOffset = $offset;
                $closeElement->endOffset = $offset + mb_strlen($match[0][0]);

                return $closeElement;
            }

            return null;
        }, $matches);

        return array_filter($nodes);
    }

    private function normalizeLineEndings(string $content): string
    {
        return str_replace(['\r\n', '\r'], '\n', $content);
    }
}
