<?php

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\NotaFiscalItem;
use App\Services\BaseService;
use Illuminate\Http\Request;

class NotaFiscalItemExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = NotaFiscalItem::class;
        $this->filters = ['id_filial', 'ped_num', 'id_produto', 'nfs_doc', 'nfs_serie', 'nfs_status'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_NOTA_FISCAL_ITEM_EXTERNA;
        $this->firstOrNew = ['id_filial', 'ped_num', 'id_produto'];
        $this->model = "\NotaFiscalItem";
        $this->fields = [
            "id_filial",
            "ped_num",
            "id_produto",
            "nfs_doc",
            "nfs_serie",
            "nfs_status",
            "nfs_qtd",
            "nfs_unitario",
            "nfs_desconto",
            "nfs_descto",
            "nfs_total",
            "ped_qtd",
            "ped_total",
            "nfs_custo"
        ];
    }
    public function destroyPersonalizado(Request $request)
    {
        if (isset($request->pedNum)) {
            $where = ["ped_num" => $request->pedNum, "id_filial" => $request->idFilial, "id_produto" => $request->idProduto];
        } else {
            $where = ["nfs_doc" => $request->nfsDoc, "nfs_serie" => $request->nfsSerie, "id_filial" => $request->idFilial, "id_produto" => $request->idProduto];
        }
        return $this->destroyWhere($where);
    }
}
