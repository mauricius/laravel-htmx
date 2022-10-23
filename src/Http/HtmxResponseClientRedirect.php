<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Http;

use Illuminate\Http\Response;

class HtmxResponseClientRedirect extends Response
{
    public function __construct(string $to)
    {
        $headers = [
            'HX-Redirect' => $to
        ];

        parent::__construct('', 200, $headers);
    }
}
