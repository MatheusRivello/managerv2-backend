<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Tenant\EstoqueCliente;
use Illuminate\Http\Request;

class EstoqueClienteMobileController extends BaseMobileController
{
    protected $className;

    public function __construct()
    {
        $this->className = "Estoque_cliente";
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
        $this->model = EstoqueCliente::class;
    }

    public function inserir(Request $request)
    {
        $vetor = $request->json;

        //VERIFICA SE O JSON É INVÁLIDO
        if (is_null($vetor)) {
            return response()->json($this->retorna_msg_erro(ERRO_JSON_INVALIDO, "", $vetor, "json"), HTTP_CREATED);
        }

        $retorno = [];

        foreach ($vetor as $item) {
            $idMobile = $item['id'];
            $item['id'] = null;

            $registro = $this->model::firstOrNew(
                [
                    'id_vendedor' => $item['id_vendedor'],
                    'id_cliente' => $item['id_cliente'],
                    'id_produto' => $item['id_produto'],
                    'dt_coleta' => $item['data']
                ]
            );
            $registro->id_filial = $this->usuarioLogado()->fk_empresa;
            $registro->quantidade = $item['quantidade'];
            $registro->valor_gondula = $item['valor_gondula'];
            $registro->markup = $item['markup'];

            if ($registro->save()) {
                $retorno[] = [
                    'id_nuvem' => $registro,
                    'id_mobile' => $idMobile
                ];
            }
        }

        if (count($retorno) > 0) {
            $resposta = [
                'code' => HTTP_OK,
                'status' => 'sucesso',
                'data' => $retorno
            ];
        } else {
            $resposta = [
                'code' => HTTP_NOT_ACCEPTABLE,
                'status' => 'sucesso_parcial',
                'mensagem' => 'Alguns dados não foram inseridos',
                'data' => $retorno
            ];
        }

        return response()->json($resposta, HTTP_CREATED);
    }
}
