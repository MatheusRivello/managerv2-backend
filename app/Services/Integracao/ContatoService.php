<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\Contato;

class ContatoService extends IntegracaoService
{
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Contato::class;
        $this->path = CONTATO_INTEG;
        $this->where = fn($obj) => [
            'id_cliente' => $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id,
            'con_cod'=>$obj->con_cod
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->con_cod = $obj->con_cod;
            $model->telefone = $obj->telefone;
            $model->email = $obj->email;
            $model->nome = $obj->nome;
            $model->aniversario = $obj->aniversario;
            $model->hobby = $obj->hobby;
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