<?php

namespace App\Services\api\Tenant;

use App\Models\Tenant\TituloFinanceiro;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class TituloFinanceiroService extends BaseService
{
    public function retornaParametros()
    {
        //Estrutura para montar o Sql
        $injectionSql = [
            "select" => [
                DB::raw("IF(titulo_financeiro.dt_vencimento < CURRENT_DATE,'vencido', 'aVencer') AS statusVencimento"),
                DB::raw("titulo_financeiro.id AS idTitulo"),
                DB::raw("titulo_financeiro.numero_doc numeroDoc"),
                DB::raw("cliente.razao_social AS  nomeCliente"),
                DB::raw("forma_pagamento.descricao AS descricaoFormaPgto"),
                DB::raw("titulo_financeiro.valor_original AS valorOriginal"),
                DB::raw("titulo_financeiro.parcela"),
                DB::raw("titulo_financeiro.valor"),
                DB::raw("titulo_financeiro.dt_emissao AS dtEmissao"),
                DB::raw("titulo_financeiro.dt_vencimento AS dtVencimento"),
                DB::raw("titulo_financeiro.multa_juros AS multaJuros"),
                DB::raw("(titulo_financeiro.valor + titulo_financeiro.multa_juros) AS valorAtual"),
                DB::raw("titulo_financeiro.linha_digitavel AS linhaDigitavel"),
                DB::raw("IF(titulo_financeiro.status,'Pago', 'Em Aberto') as statusPagamento")
            ],
            "join" => [
                [
                    "tabela" => "cliente",
                    "param1" => "titulo_financeiro.id_cliente",
                    "operador" => "=",
                    "param2" => "cliente.id"
                ],
                [
                    "tabela" => "forma_pagamento",
                    "param1" => "titulo_financeiro.id_forma_pgto",
                    "operador" => "=",
                    "param2" => "forma_pagamento.id"
                ],
                [
                    "tabela" => "filial",
                    "param1" => "cliente.id_filial",
                    "operador" => "=",
                    "param2" => "filial.id"
                ],
                [
                    "tabela" => "vendedor",
                    "param1" => "cliente.ven_cod",
                    "operador" => "=",
                    "param2" => "vendedor.id"
                ],
            ],
            "groupBy" => [["titulo_financeiro.id"]],
            "where" => [],
            "whereIn" => [],
            "executeQuery" => FALSE
        ];

        return $injectionSql;
    }

    public function retornaParametrosHead()
    {
        //Estrutura para montar o Sql
        $injectionSql = [
            "select" => [
                DB::raw("IF(titulo_financeiro.dt_vencimento < CURRENT_DATE,'vencido', 'aVencer') AS statusVencimento"),
                DB::raw("FORMAT(SUM(titulo_financeiro.valor),2,'pt_BR') AS valor"),
                DB::raw("FORMAT(SUM(titulo_financeiro.multa_juros),2,'pt_BR') AS multaJuros"),
                DB::raw("FORMAT(SUM(titulo_financeiro.valor_original),2,'pt_BR') AS valorOriginal")
            ],
            "join" => $this->retornaParametros()["join"],
            "groupBy" => [DB::raw("IF(titulo_financeiro.dt_vencimento < CURRENT_DATE,'vencido', 'aVencer')")],
            "where" => $this->retornaParametros()["where"],
            "whereIn" => $this->retornaParametros()["whereIn"]
        ];

        return $injectionSql;
    }

    public function montaArrayWhere($request)
    {
        $arrayWhere = [
            "where" => [],
            "whereIn" => [],
        ];

        //Monta where de filial
        if (isset($request->filial) && !empty($request->filial) && count($request->filial) > 0)
            array_push($arrayWhere['whereIn'], ["campo" => "cliente.id_filial", "valor" => $request->filial]);

        //Monta where de vendedor de acordo pela tabela de cliente
        if (isset($request->vendedor) && !empty($request->vendedor) && count($request->vendedor) > 0)
            array_push($arrayWhere['whereIn'], ["campo" => "cliente.ven_cod", "valor" => $request->vendedor]);

        //Monta where de forma de pagamento
        if (isset($request->formaPgto) && !empty($request->formaPgto) && count($request->formaPgto) > 0)
            array_push($arrayWhere['whereIn'], ["campo" => "titulo_financeiro.id_forma_pgto", "operador" => "=", "valor" => $request->formaPgto]);

        //Filtra a data de acordo com o tipo informado
        if ($request->porQualData === "dtEmissao" && isset($request->dtInicioUs) && isset($request->dtFimUs)) {
            array_push($arrayWhere['where'], ["campo" => "titulo_financeiro.dt_emissao", "operador" => ">=", "valor" => $request->dtInicioUs]);
            array_push($arrayWhere['where'], ["campo" => "titulo_financeiro.dt_emissao", "operador" => "<=", "valor" => $request->dtFimUs]);
        } else if ($request->porQualData === "dtVencimento" && isset($request->dtInicioUs) && isset($request->dtFimUs)) {
            array_push($arrayWhere['where'], ["campo" => "titulo_financeiro.dt_vencimento", "operador" => ">=", "valor" => $request->dtInicioUs]);
            array_push($arrayWhere['where'], ["campo" => "titulo_financeiro.dt_vencimento", "operador" => "<=", "valor" => $request->dtFimUs]);
        }

        return $arrayWhere;
    }

    public function montaArrayJoin($restricaoVendedorCliente)
    {
        $arrayJoin = [];

        //Verifica se existe restrição de vendedor com cliente
        if ($restricaoVendedorCliente)
            array_push(
                $arrayJoin,
                [
                    "tabela" => "vendedor_cliente",
                    "param1" => "titulo_financeiro.id_cliente",
                    "operador" => "=",
                    "param2" => "vendedor_cliente.id_cliente"
                ]
            );

        return $arrayJoin;
    }

    public function resultadoAgrupadoStatus($descricao, $arrayJoin, $arrayWhere, $arrayWhereIn, $limit, $status, $camposAgrupamento, $agruparPorStatus = TRUE, $mostrarStatusVencimento = TRUE)
    {
        $resultado = TituloFinanceiro::select(
            DB::raw("$descricao AS descricao"),
            DB::raw("FORMAT(SUM(titulo_financeiro.valor),2,'pt_BR') AS valorAntigo"),
            DB::raw("FORMAT(SUM(titulo_financeiro.multa_juros),2,'pt_BR') AS multaJuros"),
            DB::raw("FORMAT(SUM(titulo_financeiro.valor + titulo_financeiro.multa_juros),2,'pt_BR') AS valorAtual")
        )
            ->orderBy("valorAtual", "DESC")
            ->distinct()
            ->limit($limit);

        if (isset($status) && !is_null($status)) {
            $resultado->having("statusVencimento", "=", $status);
        }

        if ($mostrarStatusVencimento) {
            $resultado->selectRaw("IF(titulo_financeiro.dt_vencimento < CURRENT_DATE,'vencido', 'aVencer') AS statusVencimento");
        }

        if (is_bool($agruparPorStatus) && $agruparPorStatus) {
            $resultado->groupBy(
                DB::raw("IF(titulo_financeiro.dt_vencimento < CURRENT_DATE, 'vencido', 'aVencer')"),
                $camposAgrupamento
            );
        } else {
            $resultado->groupBy(
                $camposAgrupamento
            );
        }

        foreach ($arrayJoin as $join) {
            $resultado->join(
                $join["tabela"],
                $join["param1"],
                $join["operador"],
                $join["param2"]
            );
        }

        foreach ($arrayWhere as $where) {
            $resultado->where($where["campo"], $where["operador"], $where["valor"]);
        }

        foreach ($arrayWhereIn as $whereIn) {
            $resultado->whereIn($whereIn["campo"], $whereIn["valor"]);
        }
        
        return $resultado->get();
    }
}
