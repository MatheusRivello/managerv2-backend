<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Vendedor;
use App\Services\BaseService;
use Illuminate\Http\Request;

class VendedorExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Vendedor::class;
        $this->tabela = "vendedor";
        $this->filters = ['id', 'nome', 'status', 'usuario', 'supervisor', 'gerente'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_VENDEDOR_EXTERNA;
        $this->firstOrNew = ['nome', 'status', 'usuario', 'supervisor', 'gerente'];
        $this->fields = [
            'id',
            'nome',
            'status',
            'usuario',
            'senha',
            'supervisor',
            'gerente',
            'sequencia_pedido',
            'tipo',
            'saldoVerba'
        ];
        $this->modelComBarra = '\Vendedor';
    }

    public function storeVendedor(Request $request)
    {
        $where = ['id' => $request->id];
        $this->destroyWhere($where);
        return $this->storePersonalizado($request);
    }
}
