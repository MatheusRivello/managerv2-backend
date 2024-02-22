<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Central\PeriodoSincronizacao;
use App\Models\Tenant\TituloFinanceiro;
use App\Services\api\Tenant\TituloFinanceiroService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TituloFinanceiroController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new TituloFinanceiroService();
    }

    /**
     * Retorna os dados para a lista de titulos
     */

    /**
     * @OA\Get(
     *     path="/api/tenant/financeiro/lista",
     *     summary="Lista os titulos financeiros",
     *     description="Lista os titulos financeiros.",
     *     operationId="Lista de titulos financeiros",
     *     tags={"Titulos financeiro"},
     *     @OA\Parameter(
     *         name="paginate",
     *         in="query",
     *         description="Se trará paginado ou não ",
     *         required=false,
     *         @OA\Schema(type="boolean", example="1", description="Paginado ?")
     *     ),
     *     @OA\Parameter(
     *         name="totalHead",
     *         in="query",
     *         description="Cabeçalho com os totais do pedido",
     *         required=false,
     *         @OA\Schema(type="boolean", example="1", description="ID do vendedor")
     *     ),
     *     @OA\Parameter(
     *         name="filial",
     *         in="query",
     *         description="IDs das filiais nos quais deverão ser filtrados os titulos financeiros",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="IDs das filiais que detém os titulos")
     *     ),
     *     @OA\Parameter(
     *         name="vendedor",
     *         in="query",
     *         description="Vendedor responsável pelo título financeiro.",
     *         required=false,
     *         @OA\Schema(type="string", example="Walace", description="vendedor responsável.")
     *     ),
     *     @OA\Parameter(
     *         name="formaPgto",
     *         in="query",
     *         description="Formas de pagamento dos titulos financeiros",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID do vendedor")
     *     ),
     *     @OA\Parameter(
     *         name="statusVencimento",
     *         in="query",
     *         description="Status de vencimento dos titulos financeiros.",
     *         required=false,
     *         @OA\Schema(type="string", example="1", description="Status de vencimento dos titulos financeiros.")
     *     ),
     *     @OA\Parameter(
     *         name="porQualData",
     *         in="query",
     *         description="Por qual tipo de data.",
     *         required=false,
     *         @OA\Schema(type="string", example="dtVencimento", description="Por qual tipo de data será filtrado.")
     *     ),
     *     @OA\Parameter(
     *         name="dtInicioUs",
     *         in="query",
     *         description="Data inicial de pesquisa",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-05-06", description="Data inicial da pesquisa.")
     *     ),
     *     @OA\Parameter(
     *         name="dtFimUs",
     *         in="query",
     *         description="Data final de pesquisa.",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-05-06", description="Data final da pesquisa.")
     *     ),
     *     @OA\Parameter(
     *         name="limite",
     *         in="query",
     *         description="Limite de titulos financeiros.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="Limite de titulos")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de titulos financeiros.",
     *         @OA\JsonContent( 
     *             @OA\Property(property="resultado", type="object",
     *                    @OA\Property(property="current_page" , type="integer"),
     *                    @OA\Property(property="data" , type="array", @OA\Items(
     *                          @OA\Property(property="statusVencimento", type="string"),
     *                          @OA\Property(property="idTitulo", type="integer"),
     *                          @OA\Property(property="numeroDoc", type="string"),
     *                          @OA\Property(property="nomeCliente", type="string"),
     *                          @OA\Property(property="descricaoFormaPgto", type="string"),
     *                          @OA\Property(property="valorOriginal", type="string"),
     *                          @OA\Property(property="parcela", type="string"),
     *                          @OA\Property(property="valor", type="string"),
     *                          @OA\Property(property="dtEmissao", type="string"),
     *                          @OA\Property(property="dtVencimento", type="string"),
     *                          @OA\Property(property="multaJuros", type="string"),
     *                          @OA\Property(property="valorAtual", type="string"),
     *                          @OA\Property(property="linhaDigitavel", type="string"),
     *                          @OA\Property(property="statusPagamento", type="string")
     *                    ) 
     *                 )
     *             ),    
     *         )
     *      ), 
     *     @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *     )
     *      
     * )
     **/
    public function getTituloFinanceiro(Request $request)
    {
        try {
            $verificaConfig = PeriodoSincronizacao::select("restricao_vendedor_cliente")
                ->where([
                    ['fk_empresa', $this->service->usuarioLogado()->fk_empresa]
                ])->first();

            $arrayWhere = array_merge(
                $this->service->retornaParametros()["where"],
                $this->service->montaArrayWhere($request)["where"]
            );

            $arrayWhereIn = array_merge(
                $this->service->retornaParametros()["whereIn"],
                $this->service->montaArrayWhere($request)["whereIn"]
            );

            $arrayJoin = array_merge(
                $this->service->retornaParametros()["join"],
                $this->service->montaArrayJoin($verificaConfig->restricao_vendedor_cliente)
            );

            $query = TituloFinanceiro::distinct();
            $headQuery = TituloFinanceiro::select($this->service->retornaParametrosHead()['select']);

            foreach ($arrayJoin as $join) {
                $query->join($join["tabela"], $join["param1"], $join["operador"], $join["param2"]);
            }

            foreach ($arrayWhere as $where) {
                $query->where($where["campo"], $where["operador"], $where["valor"]);
                $headQuery->where($where["campo"], $where["operador"], $where["valor"]);
            }

            foreach ($arrayWhereIn as $whereIn) {
                $query->whereIn($whereIn["campo"], $whereIn["valor"]);
            }

            if (isset($request->statusVencimento) && (strtolower($request->statusVencimento) === "avencer" || strtolower($request->statusVencimento) === "vencido")) {
                $query->where(
                    DB::raw("IF(titulo_financeiro.dt_vencimento < CURRENT_DATE,'vencido', 'avencer')"),
                    $request->statusVencimento
                );
            }

            $query->select($this->service->retornaParametros()["select"]);

            //Permissão para retornar os dados para o cabeçalho
            if (isset($request->totalHead) && $request->totalHead === true) {

                $resultado['totalHead'] =  $headQuery->groupBy($this->service->retornaParametrosHead()['groupBy'])->get();

                $resultado["agrupadoPorCliente"]["vencido"] = $this->service->resultadoAgrupadoStatus(
                    "cliente.razao_social",
                    $arrayJoin,
                    $arrayWhere,
                    $arrayWhereIn,
                    10,
                    "vencido",
                    ["cliente.razao_social", "cliente.id"]
                );

                $resultado["agrupadoPorCliente"]["aVencer"] = $this->service->resultadoAgrupadoStatus(
                    "cliente.razao_social",
                    $arrayJoin,
                    $arrayWhere,
                    $arrayWhereIn,
                    10,
                    "aVencer",
                    ["cliente.razao_social", "cliente.id"]
                );

                $resultado["agrupadoPorCliente"]["total"] = $this->service->resultadoAgrupadoStatus(
                    "cliente.razao_social",
                    $arrayJoin,
                    $arrayWhere,
                    $arrayWhereIn,
                    10,
                    NULL,
                    ["cliente.razao_social", "cliente.id"],
                    FALSE,
                    FALSE
                );

                //Agrupado por forma de pagamento
                $resultado["agrupadoPorFormaDePagamento"]["vencido"] = $this->service->resultadoAgrupadoStatus(
                    "forma_pagamento.descricao",
                    $arrayJoin,
                    $arrayWhere,
                    $arrayWhereIn,
                    10,
                    "vencido",
                    ["forma_pagamento.id", "forma_pagamento.descricao"]
                );

                $resultado["agrupadoPorFormaDePagamento"]["aVencer"] = $this->service->resultadoAgrupadoStatus(
                    "forma_pagamento.descricao",
                    $arrayJoin,
                    $arrayWhere,
                    $arrayWhereIn,
                    10,
                    "aVencer",
                    ["forma_pagamento.descricao", "forma_pagamento.id"],
                );

                $resultado["agrupadoPorFormaDePagamento"]["total"] = $this->service->resultadoAgrupadoStatus(
                    "forma_pagamento.descricao",
                    $arrayJoin,
                    $arrayWhere,
                    $arrayWhereIn,
                    10,
                    NULL,
                    ["forma_pagamento.descricao", "forma_pagamento.id"],
                    FALSE,
                    FALSE
                );
            }

            $resultado['resultado'] = isset($request->paginate) && $request->paginate === false ? $query->get()
                ->chunk(30) : $query->paginate(!is_null($request->limite) || is_int($request->limite) ? $request->limite : 100);
            return $this->service->verificarErro(
                $resultado
            );
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function totalHead(Request $request)
    {
        try {
            $verificaConfig = PeriodoSincronizacao::select("restricao_vendedor_cliente")
                ->where([
                    ['fk_empresa', $this->service->usuarioLogado()->fk_empresa]
                ])->first();

            $arrayWhere = array_merge(
                $this->service->retornaParametros()["where"],
                $this->service->montaArrayWhere($request)["where"]
            );

            $arrayWhereIn = array_merge(
                $this->service->retornaParametros()["whereIn"],
                $this->service->montaArrayWhere($request)["whereIn"]
            );

            $arrayJoin = array_merge(
                $this->service->retornaParametros()["join"],
                $this->service->montaArrayJoin($verificaConfig->restricao_vendedor_cliente)
            );

            $query = TituloFinanceiro::distinct();
            $headQuery = TituloFinanceiro::select($this->service->retornaParametrosHead()['select']);

            foreach ($arrayJoin as $join) {
                $query->join($join["tabela"], $join["param1"], $join["operador"], $join["param2"]);
            }

            foreach ($arrayWhere as $where) {
                $query->where($where["campo"], $where["operador"], $where["valor"]);
                $headQuery->where($where["campo"], $where["operador"], $where["valor"]);
            }

            foreach ($arrayWhereIn as $whereIn) {
                $query->whereIn($whereIn["campo"], $whereIn["valor"]);
            }

            if (isset($request->statusVencimento) && (strtolower($request->statusVencimento) === "avencer" || strtolower($request->statusVencimento) === "vencido")) {
                $query->where(
                    DB::raw("IF(titulo_financeiro.dt_vencimento < CURRENT_DATE,'vencido', 'avencer')"),
                    $request->statusVencimento
                );
                $headQuery->where(
                    DB::raw("IF(titulo_financeiro.dt_vencimento < CURRENT_DATE,'vencido', 'avencer')"),
                    $request->statusVencimento
                );
            }

            $query->select($this->service->retornaParametros()["select"]);

            $resultado['totalHead'] =  $headQuery->groupBy($this->service->retornaParametrosHead()['groupBy'])->get();

            $resultado["agrupadoPorCliente"]["vencido"] = $this->service->resultadoAgrupadoStatus(
                "cliente.razao_social",
                $arrayJoin,
                $arrayWhere,
                $arrayWhereIn,
                10,
                "vencido",
                ["cliente.razao_social", "cliente.id"]
            );

            $resultado["agrupadoPorCliente"]["aVencer"] = $this->service->resultadoAgrupadoStatus(
                "cliente.razao_social",
                $arrayJoin,
                $arrayWhere,
                $arrayWhereIn,
                10,
                "aVencer",
                ["cliente.razao_social", "cliente.id"]
            );

            $resultado["agrupadoPorCliente"]["total"] = $this->service->resultadoAgrupadoStatus(
                "cliente.razao_social",
                $arrayJoin,
                $arrayWhere,
                $arrayWhereIn,
                10,
                NULL,
                ["cliente.razao_social", "cliente.id"],
                FALSE,
                FALSE
            );

            //Agrupado por forma de pagamento
            $resultado["agrupadoPorFormaDePagamento"]["vencido"] = $this->service->resultadoAgrupadoStatus(
                "forma_pagamento.descricao",
                $arrayJoin,
                $arrayWhere,
                $arrayWhereIn,
                10,
                "vencido",
                ["forma_pagamento.id", "forma_pagamento.descricao"]
            );

            $resultado["agrupadoPorFormaDePagamento"]["aVencer"] = $this->service->resultadoAgrupadoStatus(
                "forma_pagamento.descricao",
                $arrayJoin,
                $arrayWhere,
                $arrayWhereIn,
                10,
                "aVencer",
                ["forma_pagamento.descricao", "forma_pagamento.id"],
            );

            $resultado["agrupadoPorFormaDePagamento"]["total"] = $this->service->resultadoAgrupadoStatus(
                "forma_pagamento.descricao",
                $arrayJoin,
                $arrayWhere,
                $arrayWhereIn,
                10,
                NULL,
                ["forma_pagamento.descricao", "forma_pagamento.id"],
                FALSE,
                FALSE
            );

            return $this->service->verificarErro(
                $resultado
            );
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
