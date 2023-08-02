<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\View;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class BladeFragment
{
    public const OPEN = 'fragment';
    public const CLOSE = 'endfragment';

    public static function render(string $view, string $fragment, array $data = []): string
    {
        $path = View::make($view, $data)->getPath();

        $content = File::get($path);

        $output = self::captureFragmentFromContent($fragment, $path, $content);

        return Blade::render($output, $data);
    }

    private static function captureFragmentFromContent(string $fragment, string $path, string $content): string
    {
        $parser = new BladeFragmentParser(self::OPEN, self::CLOSE);

        $nodes = $parser->parse($content);

        $node = array_filter($nodes, function (OpenFragmentElement|CloseFragmentElement $node) use ($fragment) {
            return $node instanceof OpenFragmentElement && $node->name === $fragment;
        });

        throw_if(empty($node), "No fragment called \"$fragment\" exists in \"$path\"");

        throw_if(count($node) > 1, "Multiple fragments called \"$fragment\" exists in \"$path\"");

        $nestedOccurrences = 0;

        $openElement = null;
        $closeElement = null;

        foreach ($nodes as $node) {
            if ($openElement === null && $node instanceof OpenFragmentElement) {
                if($node->name === $fragment) {
                    $openElement = $node;

                    continue;
                }
            }

            if ($openElement !== null && $node instanceof OpenFragmentElement) {
                $nestedOccurrences++;

                continue;
            }

            if ($openElement !== null && $node instanceof CloseFragmentElement) {
                if ($nestedOccurrences === 0) {
                    $closeElement = $node;

                    break;
                } else {
                    $nestedOccurrences--;
                }
            }
        }

        return mb_substr(
            $content,
            $openElement->endOffset,
            $closeElement->startOffset - $openElement->endOffset
        );
    }
}
