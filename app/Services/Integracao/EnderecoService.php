<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\Endereco;

class EnderecoService extends IntegracaoService
{
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = Endereco::class;
        $this->path = ENDERECO_INTEG;
        $this->where = fn($obj) => [
            'id_retaguarda' => $obj->id_retaguarda
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_retaguarda = $obj->id_retaguarda;
            $model->id_cliente = $this->clientes[$obj->id_filial . "-" . $obj->id_cliente]->id;
            $model->tit_cod = $obj->tit_cod;
            $model->id_cidade = $obj->id_cidade;
            $model->cep = $obj->cep;
            $model->logradouro = $obj->logradouro;
            $model->numero = $obj->numero;
            $model->complemento = $obj->complemento;
            $model->bairro = $obj->bairro;
            $model->uf = $obj->uf;
            $model->latitude = $obj->latitude;
            $model->longitude = $obj->longitude;
            $model->referencia = $obj->referencia;
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