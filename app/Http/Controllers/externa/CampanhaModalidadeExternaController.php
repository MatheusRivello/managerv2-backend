<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\CampanhaModalidade;
use App\Services\BaseService;
use Illuminate\Http\Request;

class CampanhaModalidadeExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = CampanhaModalidade::class;
        $this->filters = ['id_campanha', 'id_retaguarda'];
        $this->rulePath = RULE_CAMPANHA_MODALIDADE_EXTERNA;
        $this->firstOrNew = ['id_campanha', 'id_retaguarda'];
        $this->fields = ['id_campanha', 'id_retaguarda'];
        $this->tabela = 'campanha_modalidade';
        $this->modelComBarra ='\CampanhaModalidade';
    }

    public function destroyCampanhaModalidade(?Request $request, $where = null)
    {
        if (isset($request)) {
            $where = ["id_campanha" => $request->idCampanha, 'id_retaguarda' => $request->idRetaguarda];
        }
        return $this->destroyWhere($where);
    }

    public function storeCampanhaModalidade(Request $request)
    {
        $where = ["id_campanha" => $request->idCampanha, 'id_retaguarda' => $request->idRetaguarda];
        $this->destroyCampanhaModalidade(null, $where);
        return $this->storePersonalizado($request);
    }
}
