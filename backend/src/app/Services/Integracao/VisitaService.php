<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\Visita;

class VisitaService extends IntegracaoService
{
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Visita::class;
        $this->path = VISITA_INTEG;
        $this->where = fn($obj) => ['id' => $obj->id_retaguarda];
        $this->updateFields = function ($model, $obj) {
            $model->id_filial = $obj->id_filial;
            $model->id_vendedor = $obj->id_vendedor;
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->dt_marcada = $obj->dt_marcada;
            $model->hora_marcada = $obj->hora_marcada;
            $model->ordem = $obj->ordem;
            $model->status = $obj->status;
            $model->sinc_erp = 0;
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