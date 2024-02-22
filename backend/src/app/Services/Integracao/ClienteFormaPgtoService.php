<?php
namespace App\Services\Integracao;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\ClienteFormaPgto;
use App\Models\Tenant\FormaPagamento;

class ClienteFormaPgtoService extends IntegracaoService
{
    protected $formasPagamento;
    protected $clientes;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->ModelClass = ClienteFormaPgto::class;
        $this->path = CLIENTE_FORMA_DE_PAGAMENTO_INTEG;
        $this->where = fn($obj) => [
            'id_forma_pgto' => $this->formasPagamento[$obj->id_retaguarda]->id,
            'id_cliente' => $this->clientes[$obj->id_filial . "-" . $obj->id_cliente]->id
        ];
        $this->updateFields = function ($model, $obj) {
            $model->id_forma_pgto = $this->formasPagamento[$obj->id_retaguarda]->id;
            $model->id_cliente = $this->clientes[$obj->id_filial . "-" . $obj->id_cliente]->id;
            return $model;
        };
    }

    protected function getDataFromReferencedTables()
    {
        $this->formasPagamento = FormaPagamento::get()->keyBy('id_retaguarda');
        $this->clientes = Cliente::get()
        ->keyBy(function($cliente) {
            return $cliente->id_filial . '-' . $cliente->id_retaguarda;
        });
    }
}