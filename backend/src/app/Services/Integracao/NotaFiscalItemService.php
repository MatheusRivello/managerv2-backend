<?php

namespace App\Services\Integracao;

use App\Models\Tenant\NotaFiscalItem;
use App\Models\Tenant\Produto;

class NotaFiscalItemService extends IntegracaoService
{
    protected $produtos;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = NotaFiscalItem::class;
        $this->path = NOTA_FISCAL_ITEM_INTEG;
        $this->requestParams = [
            'numeroDiasConsultar' => intval($this->getConfigIntegrador('qtd_dias_nota_fiscal', $params['tenant']))
        ];
        $this->where = fn ($obj) => [
            'id_filial' => $obj->id_filial,
            'ped_num' => $obj->ped_num,
            'id_produto' => $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->ped_num = $obj->ped_num;
            $model->id_produto = $this->produtos[$obj->id_filial . '-' . $obj->id_produto]->id;
            $model->nfs_qtd = $obj->nfs_qtd;
            $model->nfs_desconto = $obj->nfs_desconto;
            $model->nfs_descto = $obj->nfs_descto;
            $model->nfs_total = $obj->nfs_total;
            $model->ped_qtd = $obj->ped_qtd;
            $model->ped_total = $obj->ped_total;
            $model->nfs_status = $obj->nfs_status;
            $model->nfs_unitario = $obj->nfs_unitario;
            $model->nfs_custo = $obj->nfs_custo;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->produtos = Produto::get()
        ->keyBy(function($produto) {
            return $produto->id_filial . '-' . $produto->id_retaguarda;
        });
    }
}
