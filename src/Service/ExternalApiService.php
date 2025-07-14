<?php

class ExternalApiService
{
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/config.php';
    }

    public function login($username, $password)
    {
        $response = @file_get_contents($this->config['api_url'] . '/login', false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header'  => "Content-Type: application/json",
                'content' => json_encode([
                    'username' => $username,
                    'password' => $password
                ])
            ]
        ]));

        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);
        return $data['token'] ?? null;
    }

    public function getProductsWithToken($token)
    {
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $token
            ]
        ];

        $response = @file_get_contents($this->config['api_url'] . '/products', false, stream_context_create($opts));

        if ($response === false) {
            return null;
        }

        return json_decode($response, true);
    }
}
