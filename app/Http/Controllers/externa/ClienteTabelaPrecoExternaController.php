<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\ClienteTabelaPreco;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ClienteTabelaPrecoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ClienteTabelaPreco::class;
        $this->tabela = 'cliente_tabela_preco';
        $this->filters = ['id_cliente', 'id_tabela'];
        $this->modelComBarra='\ClienteTabelaPreco';
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CLIENTE_TABELA_PRECO_EXTERNA;
        $this->firstOrNew = ['id_cliente', 'id_tabela'];
        $this->fields = [
            'id_cliente',
            'id_tabela'
        ];
    }

    public function destroyPersonalizado(Request $request)
    {
        $where = ['id_cliente' => $request->idCliente, 'id_tabela' => $request->idTabela];
        return $this->destroyWhere($where);
    }

    public function storeClienteTabelaPreco(Request $request)
    {
        $where = ['id_cliente' => $request->idCliente, 'id_tabela'=>$request->idTabela];
        $this->destroyWhere($where);
        return $this->storePersonalizado($request);
    }
}
