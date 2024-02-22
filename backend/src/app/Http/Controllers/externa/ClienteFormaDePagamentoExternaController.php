<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\ClienteFormaPgto;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ClienteFormaDePagamentoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ClienteFormaPgto::class;
        $this->filters = ['id_cliente', 'id_forma_pgto'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CLIENTE_FORMA_DE_PAGAMENTO;
        $this->firstOrNew = ['id_cliente', 'id_forma_pgto'];
        $this->fields = [
            "id_cliente",
            "id_forma_pgto"
        ];
        $this->tabela = 'cliente_forma_pgto';
    }

    public function destroyPersonalizado(Request $request)
    {
        if (isset($request->idCliente)) {
            $where = ["id_cliente" => $request->idCliente, "id_forma_pgto" => $request->idFormaPgto];
        }
        return $this->destroyWhere($where);
    }
}
