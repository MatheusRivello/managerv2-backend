<?php

namespace App\Services\api;

use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SincronismoService extends BaseService
{
    public function verificarCamposConfigEmpresa($configuracao, $id = null)
    {
        $validator = Validator::make($configuracao, [
            "valor" => [
                'required',
                'boolean'
            ],
            "tipo" => [
                'required',
                'integer',
                Rule::in([0, 1])
            ],
            "configuracao_empresa" => [
                'integer',
                "exists:tipo_configuracao_empresa,id"
            ]
        ], $this->messages());

        $errors = $validator->errors();

        $textoErrors = [];

        foreach ($errors->all() as $message) {
            $textoErrors[] = $message . ' ';
        }

        if ($validator->fails()) {
            throw new Exception(implode($textoErrors), 409);
        } else {
            return $configuracao;
        }
    }

    public function verificarCamposPeriodoSinc($request, $id = null)
    {
        $validator = Validator::make($request, [
            "dias.Seg" => "boolean",
            "dias.Ter" => "boolean",
            "dias.Qua" => "boolean",
            "dias.Qui" => "boolean",
            "dias.Sex" => "boolean",
            "dias.Sab" => "boolean",
            "dias.Dom" => "boolean",
            "periodo" => "integer",
            "registro_lote" => "integer",
            "qtd_dias_nota_fiscal" => "integer",
            "qtd_dias_nota_fiscal_app" => "integer",
            "restricao_produto" => "boolean",
            "restricao_protabela_preco" => "boolean",
            "restricao_vendedor_cliente" => "boolean",
            "restricao_supervisor_cliente" => "boolean",
            "dt_cadastro_online" => "date",
            "dt_execucao_online" => "date",
            "dt_execucao_online_fim" => "date",
            "baixar_online" => "boolean",
            "baixar_online" => "boolean",
            "token_online_processando" => [
                "string",
                "max:80"
            ],
        ], $this->messages());

        $errors = $validator->errors();

        $textoErrors = [];

        foreach ($errors->all() as $message) {
            $textoErrors[] = $message . ' ';
        }

        if ($validator->fails()) {
            throw new Exception(implode($textoErrors), 409);
        } else {
            return $request;
        }
    }

    public function getHora($request)
    {
        if (is_array($request)) {
            $collectHora = collect($request)->forPage(0, 4);

            $hora = $collectHora->implode(',');
        } else {
            $hora = collect(explode(",", $request))->forPage(0, 4);

            $hora = $hora->implode(',');
        }

        $array = collect(explode(",", $hora))->map(function ($valor) {
            return $this->formatarHora($valor);
        });

        return $array->implode(',');
    }
}
