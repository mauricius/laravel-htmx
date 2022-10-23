<?php

namespace Mauricius\LaravelHtmx\View;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class BladeFragment
{
    public static function render(string $view, string $fragment, array $data = []): string
    {
        $path = View::make($view, $data)->getPath();

        $content = File::get($path);

        $re = sprintf('/@fragment\("%s"\)(.*)@endfragment/msU', $fragment);

        preg_match($re, $content, $matches);

        throw_if(empty($matches), "No fragment called \"$fragment\" exists in \"$path\"");

        return Blade::render($matches[1], $data);
    }
}
