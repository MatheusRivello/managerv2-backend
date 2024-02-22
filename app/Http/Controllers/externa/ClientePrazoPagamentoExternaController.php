<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\ClientePrazoPgto;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ClientePrazoPagamentoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ClientePrazoPgto::class;
        $this->filters = ['id_cliente','id_forma_pgto'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CLIENTE_PRAZO_PAGAMENTO_EXTERNA;
        $this->firstOrNew = ['id_cliente'];
        $this->fields = [
            'id_cliente',
            'id_prazo_pgto'
        ];
        $this->tabela='cliente_prazo_pgto';
    }
    public function destroyPersonalizado(Request $request)
    {

    if (isset($request->idCliente)) {
            $where = ["id_cliente" => $request->idCliente, "id_prazo_pgto" => $request->idPrazoPgto];
        } 
        return $this->destroyWhere($where);
    }
}

