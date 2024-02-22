<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;

use App\Models\Tenant\TituloFinanceiro;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class TituloFinanceiroExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = TituloFinanceiro::class;
        $this->filters = ['id', 'id_cliente', 'descricao', 'dt_vencimento', 'dt_pagamento', 'dt_emissao', 'status'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_TITULO_FINANCEIRO_API_EXTERNA;
        $this->firstOrNew = ['numero_doc', 'parcela', 'dt_vencimento', 'dt_pagamento', 'dt_emissao'];
        $this->fields = [
            'id_cliente',
            'id_forma_pgto',
            'id_vendedor',
            'descricao',
            'id_retaguarda',
            'numero_doc',
            'tipo_titulo',
            'parcela',
            'dt_vencimento',
            'dt_pagamento',
            'dt_competencia',
            'dt_emissao',
            'valor',
            'multa_juros',
            'status',
            'valor_original',
            'linha_digitavel'
        ];
    }

    public function showPersonalizado(Request $request)
    {
        $registro = TituloFinanceiro::where(function ($query) use ($request) {
            if (!is_null($request->id)) $query->where('id', $request->id);
            if (!is_null($request->id_cliente)) $query->where('id_cliente', $request->idCliente);
            if (!is_null($request->descricao)) $query->where('descricao', $request->descricao);
            if (!is_null($request->dtVencimentoInicial) && !is_null($request->dtVencimentoFinal)) $query->whereDate('dt_vencimento', ">=", $request->dtVencimentoInicial);
            if (!is_null($request->dtVencimentoInicial) && !is_null($request->dtVencimentoFinal)) $query->whereDate('dt_vencimento', "<=", $request->dtVencimentoFinal);
            if (!is_null($request->dtPagamentoInicial) && !is_null($request->dtPagamentoFinal)) $query->whereDate('dt_pagamento', '>=', $request->dtPagamentoInicial);
            if (!is_null($request->dtPagamentoInicial) && !is_null($request->dtPagamentoFinal)) $query->whereDate('dt_pagamento', '<=', $request->dtPagamentoFinal);
            if (!is_null($request->dtEmissaoInicial) && !is_null($request->dtEmissaoFinal)) $query->whereDate('dt_emissao', '>=', $request->dtEmissaoInicial);
            if (!is_null($request->dtEmissaoInicial) && !is_null($request->dtEmissaoFinal)) $query->whereDate('dt_emissao', '<=', $request->dtEmissaoFinal);
            if (!is_null($request->status)) $query->where('status', $request->status);
        })
            ->get();
        return $registro;
    }

    public function updateTituloFinanceiro(Request $request, $id)
    {
        try {
            if (is_null($request->id)) {
                throw new Exception(ID_NAO_INFORMADO);
            }

            $registro = TituloFinanceiro::where('id', $request->id)->update([
                'id_cliente' => $request->idCliente,
                'id_forma_pgto' => $request->idFormaPgto,
                'id_vendedor' => $request->idVendedor,
                'descricao' => $request->descricao,
                'id_retaguarda' => $request->idRetaguarda,
                'numero_doc' => $request->numeroDoc,
                'tipo_titulo' => $request->tipoTitulo,
                'parcela' => $request->parcela,
                'dt_vencimento' => $request->dtVencimento,
                'dt_pagamento' => $request->dtPagamento,
                'dt_competencia' => $request->dtCompetencia,
                'dt_emissao' => $request->dtEmissao,
                'valor' => $request->valor,
                'multa_juros' => $request->multaJuros,
                'status' => $request->status,
                'valor_original' => $request->valorOriginal,
                'linha_digitavel' => $request->linhaDigitavel
            ]);
            
            if (is_null($registro)) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO);
            }
            return response()->json(["Registro alterado"]);
        } catch (Exception $exception) {
            return response()->json(['error:' => true, 'message:' => $exception->getMessage()], $exception->getCode());
        }
    }
}
