<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Vendedor;

class VendedorService extends IntegracaoService
{
    const SUPERVISOR_DEFAULT = 999;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Vendedor::class;
        $this->path = VENDEDOR_INTEG;
        $this->where = fn($obj) => ['id' => $obj->id];
        $this->updateFields = function ($model, $obj) {
            $model->id = $obj->id;
            $model->nome = $obj->nome;
            $model->status = $obj->status;
            $model->supervisor = $obj->supervisor ?? self::SUPERVISOR_DEFAULT;
            $model->gerente = $obj->gerente;
            $model->senha = $obj->senha;
            $model->tipo = $obj->tipo;
            $model->saldoverba = $obj->saldoverba;
            return $model;
        };
    }
}