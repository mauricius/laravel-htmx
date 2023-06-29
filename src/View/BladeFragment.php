<?php

namespace Mauricius\LaravelHtmx\View;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Stillat\BladeParser\Nodes\DirectiveNode;
use Stillat\BladeParser\Parser\DocumentParser;

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
        $parser = new DocumentParser();
        $parser->setDirectiveNames([self::OPEN, self::CLOSE]);

        $nodes = $parser->parse($content);

        $output = '';
        $nestedOccurrences = 0;
        $fragmentFound = false;

        foreach($nodes as $node) {
            if (!$fragmentFound && $node instanceof DirectiveNode) {
                $value = str_replace(['"',"'"], "", $node->getValue());

                if($node->content === self::OPEN && $value === $fragment) {
                    $fragmentFound = true;

                    continue;
                }
            }

            if ($fragmentFound && $node instanceof DirectiveNode) {
                if ($node->content === self::OPEN) {
                    $nestedOccurrences++;
                } else if ($node->content === self::CLOSE) {
                    if ($nestedOccurrences === 0) {
                        $output .= $node;

                        break;
                    } else {
                        $nestedOccurrences--;
                    }
                }
            }

            if ($fragmentFound) {
                $output .= $node;
            }
        }

        throw_if(! $fragmentFound, "No fragment called \"$fragment\" exists in \"$path\"");

        return $output;
    }
}
