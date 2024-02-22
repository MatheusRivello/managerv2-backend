<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\CampanhaBeneficio;
use App\Services\BaseService;
use Illuminate\Http\Request;

class CampanhaBeneficioExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = CampanhaBeneficio::class;
        $this->filters = ['id_campanha', 'id_retaguarda'];
        $this->tabela = "campanha_beneficio";
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CAMPANHA_BENEFICIO_EXTERNA;
        $this->firstOrNew = ['id_campanha', 'id_retaguarda'];
        $this->modelComBarra='\CampanhaBeneficio';
        $this->fields = [
            'id_campanha',
            'id_retaguarda',
            'tipo',
            'codigo',
            'quantidade',
            'percentual_desconto',
            'desconto_automatico',
            'bonificacao_automatica',
            'status'
        ];
    }

    public function storeCampanhaBeneficio(Request $request)
    {
        $where = ["id_campanha" => $request->idCampanha, "id_retaguarda" => $request->idRetaguarda];
        $this->destroyWhere($where);
        return $this->storePersonalizado($request);
    }
    public function destroyPersonalizado(Request $request)
    {
        if (isset($request->idCampanha)) {
            $where = ["id_campanha" => $request->idCampanha, "id_retaguarda" => $request->idRetaguarda];
        }

        return $this->destroyWhere($where);
    }
}
