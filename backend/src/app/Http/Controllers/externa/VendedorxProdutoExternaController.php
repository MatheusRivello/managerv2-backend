<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\VendedorProduto;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class VendedorxProdutoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = VendedorProduto::class;
        $this->filters = ['id_produto', 'id_vendedor'];
        $this->tabela = 'vendedor_produto';
        $this->modelComBarra = '\VendedorProduto';
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_VENDEDOR_PRODUTO;
        $this->firstOrNew = ['id_produto', 'id_vendedor'];
        $this->fields = [
            'id_produto',
            'id_vendedor'
        ];
    }

    public function storeVendedorProduto(Request $request)
    {
        $where = ['id_produto' => $request->idProduto, 'id_vendedor' => $request->idVendedor];
        $this->destroyVendedorxProduto(null,$where);
        return $this->storePersonalizado($request);
    }

    public function destroyVendedorxProduto(?Request $request,$where=null)
    {
        try {   
                if(isset($request)){
                  $where = ['id_produto' => $request->idProduto, 'id_vendedor' => $request->idVendedor];
                }

                return $this->destroyWhere($where);
            } catch (Exception $e) {
                return response()->json(["error" => true, "message" => $e->getMessage(), "code" => $e->getCode()]);
            }
        }
}
