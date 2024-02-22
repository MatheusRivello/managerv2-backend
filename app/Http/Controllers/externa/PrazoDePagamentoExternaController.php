<?php

namespace App\Http\Controllers\externa;

use Illuminate\Http\Request;
use App\Models\Tenant\PrazoPagamento;
use App\Services\BaseService;
use Exception;

class PrazoDePagamentoExternaController extends AbstractExternaController
{
	public function __construct(BaseService $service)
	{
		$this->service = $service;
		$this->Entity = PrazoPagamento::class;
		$this->filters = ['id_retaguarda', 'descricao', 'status'];
		$this->relationCountMethodName = 'getRelacionamentosCount';
		$this->rulePath = RULE_PRAZO_DE_PAGAMENTO_EXTERNA;
		$this->firstOrNew = ['id_retaguarda', 'descricao', 'status'];
		$this->tabela = 'prazo_pagamento';
		$this->modelComBarra = '\PrazoPagamento';
		$this->fields = [
			'id_retaguarda',
			'descricao',
			'variacao',
			'valor_min',
			'status'
		];
	}

	public function storePrazoDePagamentoExterna(Request $request)
	{
		try {
			$where = ["id_retaguarda" => $request->idRetaguarda];
			$this->service->countChaveComposta(MODEL_TENANT, $this->modelComBarra, $where);
			return $this->storePersonalizado($request);
		} catch (Exception $e) {
			return response()->json(['error:' => true, 'message:' => "Já existe registro com esse Id Retaguarda."], $e->getCode());
		}
	}
}
