<?php

namespace App\Services\api\Tenant;

use App\Models\Tenant\Integracao;
use App\Services\BaseService;
use Exception;

class IntegracaoTenantService extends BaseService
{
    public function verificarDadosIntegracao($request, $idEmpresa)
    {
        switch ($request->tipo) {
            case 1:
                $model = TIPO_ELEMENTO_CLIENTE;
                $column = $request->filtro == 1 ? "id_retaguarda" : "cnpj";
                break;

            case 2:
                $model = TIPO_ELEMENTO_PRODUTO;
                $column = "id_retaguarda";
                break;

            default:
                $model = TIPO_ELEMENTO_PRODUTO;
                $column = "id_retaguarda";
                break;
        }
        
        $this->verificarCamposRequest(
            $request,
            RULE_INTEGRACAO_TENANT,
            $this->connectionTenant($idEmpresa, "database")
        );

        $idInterno = (new $model)::on($this->connectionTenant($idEmpresa))->where(
            [
                ['id_filial', $request->filial], [$column, $request->id_interno]
            ]
        )->first();
        
        if (!isset($idInterno)) {
            throw new Exception(ERROR_ID_INTERNO_ELEMENTOS, 404);
        }

        $integracao = Integracao::on($this->connectionTenant($idEmpresa))
            ->where(
                [
                    ['id', '<>', $request->id],
                    ['integrador', $request->integrador],
                    ['tipo', $request->tipo],
                    ['id_interno', $idInterno->id],
                ]
            )
            ->get();

        if ($idInterno->count() > 0 && $integracao->count() < 1) {
            $request->id_interno = $idInterno->id;

            return $request;
        } else if ($idInterno->count() > 0 && $integracao->count() > 0) {
            throw new Exception(REGISTRO_DUPLICADO, 409);
        } else {
            throw new Exception(DADO_NAO_INFORMADO, 409);
        }
    }
}
