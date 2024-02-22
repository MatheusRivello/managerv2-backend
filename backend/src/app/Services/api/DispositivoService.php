<?php

namespace App\Services\api;

use App\Models\Central\Dispositivo;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Validator;


class DispositivoService extends BaseService
{

    public function verificarConfig($configuracao, $id = null)
    {
        $validator = Validator::make($configuracao, [
            "tipo_configuracao" => [
                'required',
                'integer',
                "exists:tipo_configuracao,id"
            ],
            "valor" => [
                'required',
                'string'
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

    public function lanceExcecaoSeNaoHouverLicencaDisponivel(): void
    {
        $qtdLicenca = $this->infoTenant()->qtd_licenca;
        $qtdLicencaUtilizada = Dispositivo::on("system")->where([
            ['fk_empresa', $this->usuarioLogado()->fk_empresa],
            ['status', 1]
        ])->count();

        if ($qtdLicenca <= $qtdLicencaUtilizada)
        {
            throw new HttpClientException('Sem licença disponível. Aumente a quantidade de licenças para adicionar o dispositivo.', 405);
        }
    }
}
