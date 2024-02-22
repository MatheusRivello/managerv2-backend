<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\CampanhaParticipante;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class CampanhaParticipanteExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = CampanhaParticipante::class;
        $this->filters = ['id_campanha', 'id_retaguarda'];
        $this->rulePath = RULE_CAMPANHA_PARTICIPANTE_EXTERNA;
        $this->firstOrNew = ['id_campanha', 'id_retaguarda'];
        $this->fields = ['id_campanha', 'id_retaguarda'];
        $this->modelComBarra = '\CampanhaParticipante';
        $this->tabela = 'campanha_participante';
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
