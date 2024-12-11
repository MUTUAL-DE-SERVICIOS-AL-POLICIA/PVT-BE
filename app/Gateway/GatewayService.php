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
        $username = env('GATEWAY_USERNAME');
        $password = env('GATEWAY_PASSWORD');

        try {
            $response = $this->client->request('POST', '/api/auth/login', [
                'form_params' => [
                    'username' => $username,
                    'password' => $password,
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $cookies = $response->getHeader('Set-Cookie');

                $cookie = array_map(function ($cookie) {
                    return strtok($cookie, ';');
                }, $cookies);

                $options = [
                    'headers' => [
                        'Set-Cookie' => $cookie[0],
                    ],
                ];
                
                if (!empty($params)) {
                    $options = array_merge($options, $params);
                }
                
                $res = $this->client->request($method,$route, $options);

                $this->client->request('GET', '/api/auth/logout');

                return $res->getBody();
            }

            return [
                'status' => 'error',
                'message' => 'Error al autenticar',
                'data' => $response->getBody(),
            ];

        } catch (RequestException $e) {
            return [
                'status' => 'error',
                'message' => 'Error al realizar la solicitud',
                'data' => $e->getMessage(),
            ];
        }
    }
}
