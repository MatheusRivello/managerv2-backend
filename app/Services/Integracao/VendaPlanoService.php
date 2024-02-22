<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\VendaPlano;

class VendaPlanoService extends IntegracaoService
{
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = VendaPlano::class;
        $this->path = VENDA_PLANO_INTEG;
        $this->where = fn ($obj) => [
            'id_filial' => $obj->id_filial,
            'id_cliente' => $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id,
            'nfs_doc' => $obj->nfs_doc,
            'nfs_serie' => $obj->nfs_serie
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->nfs_num = $obj->nfs_num;
            $model->nfs_doc = $obj->nfs_doc;
            $model->nfs_serie = $obj->nfs_serie;
            $model->nfs_emissao = $obj->nfs_emissao;
            $model->tipo_saida = $obj->tipo_saida;

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
