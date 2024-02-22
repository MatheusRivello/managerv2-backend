<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\ClienteTabelaGrupo;
use App\Models\Tenant\ProtabelaPreco;

class ClienteTabelaGrupoService extends IntegracaoService
{
    protected $clientes;
    protected $proTabelaPrecos;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ClienteTabelaGrupo::class;
        $this->path = CLIENTE_TABELA_GRUPO;
        $this->where = fn($obj) => [
            'id_cliente' => $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id,
            'id_tabela' => $this->proTabelaPrecos[$obj->id_filial . '-' . $obj->id_tabela]->id,
            // verificar campo
            'id_grupo' => $obj->id_grupo
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->id_tabela = $this->proTabelaPrecos[$obj->id_filial . '-' . $obj->id_tabela]->id;
            $model->id_grupo = $obj->id_grupo;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->clientes = Cliente::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
        $this->proTabelaPrecos = ProtabelaPreco::get()
        ->keyBy(function($preco) {
            return $preco->id_filial . '-' . $preco->id_retaguarda;
        });
    }
}