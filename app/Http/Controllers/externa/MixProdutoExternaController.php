<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\MixProduto;
use App\Services\BaseService;
use Illuminate\Http\Request;

class MixProdutoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = MixProduto::class;
        $this->filters = ['id_produto', 'id_cliente'];
        $this->modelComBarra = '\MixProduto';
        $this->tabela = 'mix_produto';
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_MIX_PRODUTO_EXTERNA;
        $this->firstOrNew = ['id_produto', 'id_cliente'];
        $this->fields = [
            'id_produto',
            'id_cliente',
            'qtd_minima',
            'qtd_faturada'
        ];
    }

    public function destroyMixProduto(?Request $request, $where=null)
    {
        if (isset($request)) {
            $where = ['id_produto' => $request->idProduto, 'id_cliente' => $request->idCliente];
        }
        return $this->destroyWhere($where);
    }

    public function storeMixProduto(Request $request)
    {
        $where = ['id_produto' => $request->idProduto, 'id_cliente' => $request->idCliente,'qtd_minima'=>$request->qtdMinima,'qtd_faturada'=>$request->qtdFaturada];
        $this->destroyMixProduto(null, $where);
        return $this->storePersonalizado($request);
    }
}
