<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Http;

use Illuminate\Http\Response;

class HtmxResponseClientRefresh extends Response
{
    public function __construct()
    {
        $headers = [
            'HX-Refresh' => 'true'
        ];

        parent::__construct('', 200, $headers);
    }
}
