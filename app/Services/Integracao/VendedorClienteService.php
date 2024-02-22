<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\Vendedor;
use App\Models\Tenant\VendedorCliente;

class VendedorClienteService extends IntegracaoService
{
    protected $clientes;
    protected $vendedores;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = VendedorCliente::class;
        $this->path = VENDEDOR_CLIENTE_INTEG;
        $this->where = fn ($obj) => [
            'id_cliente' => $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id,
            'id_vendedor' => $this->vendedores[$obj->id_vendedor]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
            $model->id_vendedor = $this->vendedores[$obj->id_vendedor]->id;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->clientes = Cliente::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
        $this->vendedores = Vendedor::get()->keyBy('id');
    }
}
