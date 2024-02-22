<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\ClientePrazoPgto;
use App\Models\Tenant\PrazoPagamento;

class ClientePrazoPgtoService extends IntegracaoService
{
    protected $formasPagamento;
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ClientePrazoPgto::class;
        $this->path = CLIENTE_PRAZO_PAGAMENTO_INTEG;
        $this->where = fn($obj) => [
            'id_prazo_pgto' => $this->formasPagamento[$obj->id_retaguarda]->id,
            'id_cliente' => $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id,
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_prazo_pgto = $this->formasPagamento[$obj->id_retaguarda]->id;
            $model->id_cliente = $this->clientes[$obj->id_filial . '-' . $obj->id_cliente]->id;
           
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->formasPagamento = PrazoPagamento::get()->keyBy('id_retaguarda');
        $this->clientes = Cliente::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
    }
}