<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\ClienteTabelaPreco;
use App\Models\Tenant\ProtabelaPreco;

class ClienteTabelaPrecoService extends IntegracaoService
{
    protected $clientes;
    protected $tabelas;
    
    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ClienteTabelaPreco::class;
        $this->path = CLIENTE_TABELA_PRECO_INTEG;
        $this->where = fn($obj) => [
            'id_cliente' => $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id,
            'id_tabela' => $this->tabelas[$obj->id_filial . '-' . $obj->id_tabela]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->id_tabela = $this->tabelas[$obj->id_filial . '-' . $obj->id_tabela]->id;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->clientes = Cliente::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
        $this->tabelas = ProtabelaPreco::get()
        ->keyBy(function($tabela) {
            return $tabela->id_filial . '-' . $tabela->id_retaguarda;
        });
    }
}