<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\ProdutoCashback;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ProdutoCashBackExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ProdutoCashback::class;
        $this->filters = ['id_integrador', 'id_produto', 'cashback', 'dt_modificado'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_PRODUTO_CASHBACK;
        $this->firstOrNew = ['id_integrador', 'id_produto'];
        $this->fields =  [
            'id_integrador',
            'id_produto',
            'cashback',
            'dt_modificado'
        ];
    }

    public function storeProdutoCashback(Request $request)
    {
        $where = ['id_integrador' => $request->idIntegrador, 'id_produto' => $request->idProduto];
        $this->destroyWhere($where);
        return $this->store($request);
    }
}
