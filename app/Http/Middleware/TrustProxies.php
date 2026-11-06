<?php

namespace Muserpol\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies;

    /**
     * The proxy header mappings.
     *
     * Desde Laravel 9 el framework trae su propio TrustProxies; el paquete
     * fideloper/proxy quedo absorbido. Se mantiene el mismo conjunto de
     * cabeceras que usaba la version anterior.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_FORWARDED |
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO;
}
