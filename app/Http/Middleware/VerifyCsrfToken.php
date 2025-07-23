<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'basket/confirm',
        '/basket/confirm',
        'api/basket/confirm',
        'api/v1/basket/confirm',
    ];
}
