<?php

namespace Muserpol\Gateway;

use Illuminate\Http\Request;
use Muserpol\Gateway\GatewayService;
use Illuminate\Routing\Controller as BaseController;

class PersonController extends BaseController
{
    protected $gateway;

    public function __construct(GatewayService $gateway)
    {
        $this->gateway = $gateway;
    }

    public function persons()
    {
        return json_decode($this->gateway->send('GET', '/api/persons'),true);
    }

}
