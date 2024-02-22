<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\FormaPagamento;
use App\Services\BaseService;
use Illuminate\Http\Request;

class FormaPgtoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = FormaPagamento::class;
        $this->tabela = 'forma_pagamento';
        $this->modelComBarra = '\FormaPagamento';
        $this->filters = ['id', 'id_retaguarda', 'descricao', 'status'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_FORMA_PGTO_EXTERNA;
        $this->firstOrNew = ['id_retaguarda'];
        $this->fields = [
            'id_retaguarda',
            'descricao',
            'valor_min',
            'situacao',
            'status'
        ];
    }

    public function storeFormaPgto(Request $request)
    {
        $where = FormaPagamento::where('id_retaguarda', $request->idRetaguarda)->count();

        if ($where > 0) {
            return response()->json(['message:' => HA_REGISTRO_ATRELADO_A_ESTE_ID], 200);
        }
        return $this->store($request);
    }
}
