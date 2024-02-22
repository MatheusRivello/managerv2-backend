<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Central\LogDispositivo;
use Illuminate\Http\Request;

class LogDispositivoMobileController extends BaseMobileController
{
    protected $className;

    public function __construct()
    {
        $this->className = "Logdispositivo";
        $this->model = LogDispositivo::class;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    public function logdispositivojson(Request $request)
    {
        $array = json_decode($request->json, true);
        $qtd_inserido = 0;
        $erros = [];

        //VERIFICA SE O JSON É INVÁLIDO
        if (is_null($array)) {
            $resposta = $this->retorna_msg_erro(ERRO_JSON_INVALIDO, "", $array, "json");
            goto mensagem;
        }

        foreach ($array as $item) {
            $id_empresa = $this->usuarioLogado()->fk_empresa;

            $item["fk_empresa"] = $id_empresa;
            $item["mac"] = $this->mac;
            $id = $this->replaceLog($item);


            if ($id === NULL || $id === 0) {
                unset($item["fk_empresa"]);
                $erros[] = ["error" => "Falha ao inserir o log", "recebido" => $item];
            } else {
                $qtd_inserido++;
            }
        }

        if ((count($erros) > 0) && ($qtd_inserido > 0)) {
            $resposta = [
                'code' => HTTP_OK,
                'status' => 'sucesso_parcial',
                'mensagem' => 'Alguns dados não foram inseridos/atualizados',
                'data' => $erros
            ];
        } elseif ((count($erros) > 0) && ($qtd_inserido === 0)) {
            $resposta = [
                'code' => HTTP_NOT_FOUND,
                'status' => 'error',
                'mensagem' => 'Nenhum dado foi cadastrado',
                'data' => $erros
            ];
        } else {
            $resposta = [
                'code' => HTTP_OK,
                'status' => 'sucesso',
            ];
        }

        mensagem:
        // forçar 200 de acordo com backend antigo para passar no app
        return response()->json($resposta, 200);
    }

    private function replaceLog($data)
    {
        $log = $this->model::firstOrNew([
            "mac" => $data["mac"],
            "data" => $data["data"],
            "fk_empresa" => $data["fk_empresa"],
            "versaoApp" => $data["versaoApp"],
        ]);

        foreach($data as $key => $val) {
            $log->{$key} = $val;
        }

        $log->ip = $this->service->getIp();

        if ($log->save()) {
            $resultado = $log->id;
        } else {
            $resultado = null;
        }

        return $resultado;
    }
}
