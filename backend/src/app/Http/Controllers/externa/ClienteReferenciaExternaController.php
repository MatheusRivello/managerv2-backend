<?php

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\ClienteReferencia;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ClienteReferenciaExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ClienteReferencia::class;
        $this->filters = ['id_cliente', 'id_referencia'];
        $this->tabela='cliente_referencia';
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CLIENTE_REFERENCIA_EXTERNA;
        $this->firstOrNew = ['id_cliente'];
        $this->fields = [
            'id_cliente',
            'id_referencia'
        ];
        $this->modelComBarra='\ClienteReferencia';
    }

    public function storeClienteReferencia(Request $request)
    {
        $where =  ["id_cliente" => $request->idCliente, "id_referencia" => $request->idReferencia];
        $this->destroyWhere($where);
        return $this->storePersonalizado($request);
    }

    public function destroyPersonalizado(Request $request)
    {
        if (isset($request->idCliente)) {
            $where = ["id_cliente" => $request->idCliente, "id_referencia" => $request->idReferencia];
        }
        return $this->destroyWhere($where);
    }
}
