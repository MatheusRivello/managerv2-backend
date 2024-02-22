<?php

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\VendaPlanoProduto;
use App\Services\BaseService;
use Illuminate\Http\Request;

class VendaPlanoxProdutoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = VendaPlanoProduto::class;
        $this->filters = ['id', 'id_filial', 'id_cliente', 'nfs_num', 'nfs_doc', 'nfs_emissao', 'tipo_saida'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_VENDA_PLANO_X_PRODUTO_EXTERNA;
        $this->firstOrNew = ['id_filial', 'id_cliente', 'nfs_num',];
        $this->tabela = 'venda_plano_produto';
        $this->modelComBarra = '\VendaPlanoProduto';
        $this->fields = [
            'id',
            'id_filial',
            'id_cliente',
            'id_produto',
            'nfs_num',
            'qtd_contratada',
            'qtd_entregue',
            'qtd_disponivel',
            'valor_unitario',
            'unidade'
        ];
    }

    public function storeVendaPlanoXProduto(Request $request)
    {
        $where = ["id_filial" => $request->idFilial, 'id_cliente' => $request->idCliente, 'id_produto' => $request->idProduto];
        $this->destroyWhere($where);
        return $this->storePersonalizado($request);
    }
}
