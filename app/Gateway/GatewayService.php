<?php

namespace Muserpol\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GatewayService
{
    protected $client;

    public function __construct()
    {
        $baseUrl = env('GATEWAY_URL') . ':' . env('GATEWAY_PORT');
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 10.0,
        ]);
    }

    public function send(string $method, string $route, array $params = [])
    {
        $api_key = env('GATEWAY_API_KEY');

        try {
            $options = [
                'headers' => [
                    'x-api-key' => $api_key,
                ],
            ];

            if (!empty($params)) {
                $options = array_merge($options, $params);
            }
            $res = $this->client->request($method, $route, $options);
            return $res->getBody();
        } catch (RequestException $e) {
            return [
                'status' => 'error',
                'message' => 'Error al realizar la solicitud',
                'data' => $e->getMessage(),
            ];
        }
    }
}
