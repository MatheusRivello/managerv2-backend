<?php

namespace App\Services\Integracao;

use App\Models\Tenant\ProtabelaPreco;
use App\Models\Tenant\VendedorProtabelapreco;

class VendedorProtabelaPrecoService extends IntegracaoService
{
    protected $proTabelaPreco;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = VendedorProtabelapreco::class;
        $this->path = VENDEDOR_TABELA_PRECO;
        $this->where = fn ($obj) => [ 'id_vendedor' => $obj->id_vendedor];
        $this->updateFields = function ($model, $obj) {
            $model->id_vendedor = $obj->id_vendedor;
            $model->id_protabela_preco = $this->proTabelaPreco[$obj->id_filial . '-' . $obj->id_tabela]->id;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->proTabelaPreco = ProtabelaPreco::get()
            ->keyBy(function($tabela) {
                return $tabela->id_filial . '-' . $tabela->id_retaguarda;
            });
    }
}
