<?php

namespace App\Services\Integracao;

use App\Models\Central\ConfigIntegrador;
use GuzzleHttp\Client;

class Login {
    use Traits\Config;

    private static $instance;
    private $client;

    private $credentials;
    const URL = URL_INTEGRADOR . LOGIN_INTEG;

    private function __construct(Client $client) {
        $this->client = $client;
        $this->credentials = '{"usuario":"' . $this->getUsuario()  . '", "senha":"' . $this->getSenha() . '"}';
    }

    public function request() {
        $response = $this->client->request('POST', self::URL, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => $this->credentials,
            'verify' => false
        ]);
        $integrador = ConfigIntegrador::firstOrNew(['name' => 'access_token']);
        $integrador->name = "access_token";
        $integrador->value = json_decode($response->getBody())->data->accessToken;
        $integrador->save();
        return $this;
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Login(new Client());
        }
        return self::$instance;
    }

    public function getToken() {
        return $this->getConfigIntegrador('access_token');
    }

    private function getUsuario() {
            return env("FIREBIRD_API_USERNAME");
    }
    
    private function getSenha() {
        try {
            return env("FIREBIRD_API_PASSWORD");
        } catch (\Exception $e) {
            throw $e;
        };
    }
}