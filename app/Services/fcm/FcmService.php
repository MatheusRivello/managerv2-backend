<?php

namespace App\Services\fcm;

use App\Models\Central\Dispositivo;
use App\Services\BaseService;
use Exception;

use App\Services\fcm\Message;
use App\Services\fcm\Recipient\Device;

class FcmService extends BaseService
{
    /**
     * Metodo generico
     * @param $payload "Array com os dados"
     * @param $devices "Array com os tokens dos dispositivos"
     * @param $timeToLive
     * @return array
     */
    function notificationGeneric($payload, $devices, $timeToLive = NULL)
    {
        try {
            return $this->_returnDeMensagens($this->_addToken($payload, $devices, $timeToLive), count($devices), $devices);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * RETORNA OS TOKES DOS DISPOSITIVOS ATIVOS, ATRIBUI A VERSAO E CHAMA UM METODO DE ENVIAR NOTIFICAÇÃO
     * @param $versao Versao a ser notificada
     */
    function notificarAtualizacao($versao, $idEmpresa = NULL)
    {
        try {
            $devices = $this->_getDevices($idEmpresa);

            // se nao encontrar nenhum registro ele retonar null
            if (is_null($devices)) {
                return NULL;
            }

            $QTD_DEVICES = count($devices); // retorna a quantidade de dispositivos encontrados.

            $payload = array(
                "acao" => "configdispositivo",
                "tipo" => "int",
                "chave" => "codigoVersao",
                "valor" => $versao,
            );

            $mensagem = $this->_addToken($payload, $devices);

            return $this->_returnDeMensagens($mensagem, $QTD_DEVICES, $devices);
        } catch (Exception $e) {
            throw $e;
        }
    }

    function notificarForcarAtualizacao($forcarAtualizacao = "false", $idEmpresa = NULL)
    {
        try {
            $devices = $this->_getDevices($idEmpresa);

            // se nao encontrar nenhum registro ele retonar null
            if (is_null($devices)) {
                return NULL;
            }

            $QTD_DEVICES = count($devices); // retorna a quantidade de dispositivos encontrados.

            $payload = array(
                "acao" => "configdispositivo",
                "tipo" => "boolean",
                "chave" => "forcar_atualizacao",
                "valor" => $forcarAtualizacao,
            );

            $mensagem = $this->_addToken($payload, $devices);

            return $this->_returnDeMensagens($mensagem, $QTD_DEVICES, $devices);
        } catch (Exception $e) {
            throw $e;
        }
    }

    function rodarSql($sql, $devices)
    {
        try {
            // $devices = "f0TjLKBEjrw:APA91bGKJelawhw0-TDRD_Liuct5EYM5ci0ei2g0w1RfdziqivLHInt3HXawtRdmaQIORSwpkzi98VPWdZOBA8eDEfEVjeEPfBW8SlizsLz9xBMcQWZv6vwxNOTIjtBxzlBI2P38gzJ-";

            $payload = array(
                "acao" => "sql",
                "sql" => $sql,
                "notificar" => "true",
                "id" => "1640"
            );

            $mensagem = new Message();
            $mensagem->setData($payload);
            $mensagem->setPriority(Message::PRIORITY_NORMAL);
            $device = new Device($devices);
            $mensagem->addRecipient($device);


            $r = $this->_returnDeMensagens($mensagem, 1, $devices);
            var_dump($r);
            return $r;
        } catch (Exception $e) {
            var_dump($e);
            return $e;
        }
    }

    /**Metodo para preparar notificação do sincronismo*/
    function notificationSincronismo($idEmpresa = NULL, $dataInicioSincronismo, $tipo)
    {
        try {
            $devices = $this->_getDevices($idEmpresa);

            // se nao encontrar nenhum registro ele retonar null
            if (is_null($devices)) {
                return NULL;
            }

            $QTD_DEVICES = count($devices); // retorna a quantidade de dispositivos encontrados.

            $acao = ($tipo == 1) ? "sincronismoLiberado" : "sincronismoBloqueado"; //0=Nuvem bloqueada, 1=Nuvem liberada

            $payload = array(
                "acao" => $acao,
                "dataInicioSinc" => $dataInicioSincronismo
            );

            $mensagem = $this->_addToken($payload, $devices);

            return $this->_returnDeMensagens($mensagem, $QTD_DEVICES, $devices);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**Add tokens e dados opcionais a um vetor e retorna ele */
    protected function _addToken($payload, $devices, $timeToLive = NULL)
    {
        try {
            $mensagem = new Message();
            if (!is_null($timeToLive)) $mensagem->setTimeToLive($timeToLive);
            $mensagem->setData($payload);
            $mensagem->setPriority(Message::PRIORITY_NORMAL);

            foreach ($devices as $d) {
                if (!is_null($d["token_push"]) && $d["token_push"] != "") {
                    $device = new Device($d["token_push"]);
                    $mensagem->addRecipient($device);
                }
            }

            return $mensagem;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**Monta somente um vetor com os parametros informados no metodo*/
    protected function _returnDeMensagens($mensagem, $qtdDevices, $devices)
    {
        return [
            "DEFAULT_API_URL" => FCM_API_URL,
            "DEFAULT_API_KEY" => FCM_API_KEY,
            "MENSAGEM" => $mensagem,
            "QTD_DEVICES" => $qtdDevices,
            "DEVICES" => $devices
        ];
    }

    /**Busca somente os tokens dos dispositivos*/
    protected function _getDevices($idEmpresa = NULL)
    {
        try {
            $where =  [
                ['status', 1],
                ['id', 13862],
                ["token_push", "<>", '2']
            ];

            if (!is_null($idEmpresa)) {
                array_push($where, ["fk_empresa", $idEmpresa]);
            }
            
            return Dispositivo::where($where)->whereNotNull('token_push')->select("token_push")->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**ENVIA NOTIFICAÇÃO, É NECESSÁRIO ENVIDAR OS DADOS COMO:
     * "DEFAULT_API_URL"
     * "DEFAULT_API_KEY"
     * "MENSAGEM"
     * DENTRO DO VETOR (dados)
     * @param $dados
     */

    function send_notification($dados)
    {
        try {

            $fields = json_encode($dados["MENSAGEM"]);

            $headers = array(
                'Authorization: key=' . $dados["DEFAULT_API_KEY"],
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $dados["DEFAULT_API_URL"]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURL_HTTP_VERSION_1_0, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $result = curl_exec($ch);
            curl_close($ch);

            $serialize = serialize($dados["DEVICES"]); //serializa todos os tokens de todos os dispositivos ativos

            // $this->CI->Dispositivo_model->_salvar_log(1, "FCM", " Encaminhado notificação de atualização para ({$dados["QTD_DEVICES"]}) Dispositivos. SERIALIZE = {$serialize}", "Notificação de atualização do AFV");

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
