<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use Illuminate\Http\Request;
use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\ProtabelaIten;
use App\Services\BaseService;
use Exception;

class ProdutoTabelaExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ProtabelaIten::class;
        $this->tabela = "protabela_itens";
        $this->filters = ['id_protabela_preco', 'status'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_PRODUTO_TABELA_EXTERNA;
        $this->firstOrNew = ['id_produto', 'id_protabela_preco'];
        $this->fields = [
            'id_produto',
            'id_protabela_preco',
            'unitario',
            'status',
            'qevendamax',
            'qevendamin',
            'desconto',
            'desconto2',
            'desconto3'
        ];
        $this->modelComBarra = '\ProtabelaIten';
    }

    public function destroyPersonalizado(Request $request)
    {
        try {

            if (isset($request->idProtabelaPreco)) {
                $where = ["id_produto" => $request->idProduto, 'id_protabela_preco' => $request->idProtabelaPreco];
            }
            if (!isset($where)) {
                throw new Exception(ERRO_VARIAVEL_INDEFINIDA, 400);
            }
            return $this->destroyWhere($where);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 406);
        }
    }

    public function storeProdutoTabelaExterna(Request $request)
    {
        $where = ["id_produto" => $request->idProduto, 'id_protabela_preco' => $request->idProtabelaPreco];
        $this->destroyWhere($where);
        return $this->storePersonalizado($request);
    }
}
