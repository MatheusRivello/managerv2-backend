<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\NotaFiscal;

class NotaFiscalService extends IntegracaoService
{
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = NotaFiscal::class;
        $this->path = NOTA_FISCAL_INTEG;
        $this->requestParams = [
            'numeroDiasConsultar' => intval($this->getConfigIntegrador('qtd_dias_nota_fiscal', $params['tenant']))
        ];
        $this->where = fn ($obj) => ['id_filial' => $obj->id_filial,'nfs_doc' => $obj->nfs_doc, 'nfs_serie' => $obj->nfs_serie];
        $this->updateFields = function ($model, $obj) {
            $model->ped_total = $obj->ped_total;
            $model->ped_emissao = $obj->ped_emissao;
            $model->nfs_status = $obj->nfs_status;
            $model->prazo_pag = $obj->prazo_pag;
            $model->ped_num = $obj->ped_num;
            $model->nfs_tipo = $obj->nfs_tipo;
            $model->nfs_valbrut = $obj->nfs_valbrut;
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->nfs_emissao = $obj->nfs_emissao;
            $model->nfs_serie = $obj->nfs_serie;
            $model->nfs_doc = $obj->nfs_doc;
            $model->id_filial = $obj->id_filial;
            $model->id_vendedor = $obj->id_vendedor;
            $model->nfs_custo = $obj->nfs_custo;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->clientes = Cliente::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
    }
}
