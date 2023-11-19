<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx;

class Utils
{
    public static function containsANonNullableElement(array $arr): bool
    {
        return count($arr) !== count(array_filter($arr, 'is_null'));
    }
}
