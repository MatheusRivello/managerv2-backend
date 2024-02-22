<?php

namespace App\Services\Integracao;

abstract class SendDataToErpService
{
    use Traits\Log;

    protected $client;
    protected $path;
    protected $token;
    protected $tenant;
    protected $data;
    protected $response;
    protected $httpCode;
    protected $logFolder;

    protected function request($recursive = false)
    {
        try
        {
            $ch = curl_init();

            $className = $this->getClassName();

            $header = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $this->token,
                'tenant: ' . $this->tenant
            ];
            curl_setopt($ch, CURLOPT_URL, URL_INTEGRADOR . $this->path);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $curlInfo = [
                "url" => curl_getinfo($ch, CURLINFO_EFFECTIVE_URL),
                "header" => $header,
                "body" => json_encode($this->data)
            ];

            $this->writeLog(
                "Fazendo requisição... " . json_encode($curlInfo), 
                "/" . $this->logFolder . "/"
            );

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
        catch (\Exception $e)
        {
            $this->writeLog(
                "Ocorreu o seguinte erro na requisição: " . $e->getMessage(),
                "/" . $this->logFolder . "/erro"
            );
        }

        if ($httpCode == 401)
        {
            $this->token = Login::getInstance()->request()->getToken();
            $this->request();
        }
        else if ($this->isOrderAlreadyRegistered($httpCode, $response))
        {
            // regex para encontrar um número após ": " na string $response
            preg_match("/:\s(\d+)/", $response, $matches);

            $this->response = (object) [
                "data" => (object) [
                    "numeroPedidoInserido" => $matches[1]
                ]
            ];
            $this->httpCode = 200;
        }
        else if ($httpCode >= 400)
        {
            if (!$recursive) $this->request(true);

            $this->writeLog(
                "API de integração retornou o status code " . $httpCode . ".\n" .
                ">>>>>>>>>>>>>>>>>>>>> Requisição: " . json_encode($curlInfo) . "\n" .
                ">>>>>>>>>>>>>>>>>>>>> Resposta da API: " . json_encode($response) . "\n" .
                ">>>>>>>>>>>>>>>>>>>>> Data: " . json_encode($this->data) . "\n",
                "/" . $this->logFolder . "/erro"
            );
        }
        else {
            $this->response = json_decode($response);
            $this->httpCode = $httpCode;
        }
        
        curl_close($ch);        
    }

    protected function hasComissionError($status, $response)
    {
        return $status >= 400 && strpos($response, "Erro na tentativa de obter o percentual de comiss") != false;
    }

    protected function isOrderAlreadyRegistered($status, $response)
    {
        // Removido caracteres especiais da string
        // `.*` expressão para zero ou mais caracteres entre as partes
        return $status >= 400 && preg_match('/Pedido j.*CADASTRADO.*pedido de n.*mero: /i', $response);
    }
}