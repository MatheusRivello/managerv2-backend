<?php

namespace App\Http\Controllers\externa;

use Illuminate\Http\Request;
use App\Models\Tenant\CampanhaRequisito;
use App\Services\BaseService;
use Exception;

class CampanhaRequisitoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = CampanhaRequisito::class;
        $this->filters = [
            'id_campanha',
            'id_retaguarda',
            'tipo',
            'codigo',
            'quantidade',
            'quantidade_max',
            'percentual_desconto',
            'obrigatorio',
            'status'
        ];
        $this->modelComBarra = '\CampanhaRequisito';
        $this->tabela = 'campanha_requisito';
        $this->rulePath = RULE_CAMPANHA_REQUISITO_EXTERNA;
        $this->firstOrNew = ['id_campanha', 'id_retaguarda'];
        $this->fields = ['id_campanha', 'id_retaguarda'];
    }

    public function destroyCampanha(Request $request)
    {
        try {
            $where = ['id_campanha' => $request->idCampanha, 'id_retaguarda' => $request->idRetaguarda];
            return $this->destroyWhere($where);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message:' => $ex->getMessage()], 400);
        }
    }

    public function storeCampanha(Request $request)
    {
        try {
            $this->destroyCampanha($request);
            return $this->storePersonalizado($request);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message:' => $ex->getMessage()], 400);
        }
    }
}
