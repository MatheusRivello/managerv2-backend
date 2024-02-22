<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Meta;
use App\Models\Tenant\MetaDetalhe;
use App\Models\Tenant\Vendedor;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

define('SOMENTE_SUPERVISOR', 1);
class MetaController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }
    
    public function getMeta(Request $request)
    {
        try {
            $idFilial = !is_null($request->filial) ? $request->filial : NULL;
            $idVendedor = !is_null($request->vendedores) ? $request->vendedores : NULL;

            $dados = Meta::orderby("meta.id_vendedor", "DESC")
                ->join("vendedor", "meta.id_vendedor", "=", "vendedor.id")
                ->join("filial", "meta.id_filial", "=", "filial.id")
                ->select(
                    "meta.id",
                    "filial.id as id_filial",
                    DB::raw("IF(filial.emp_raz IS NULL OR filial.emp_raz = '', filial.emp_fan, filial.emp_raz) AS filial"),
                    DB::raw("CONCAT(vendedor.id,' - ',vendedor.nome) AS vendedor"),
                    "meta.id_retaguarda",
                    "meta.descricao",
                    "meta.tot_qtd_ven",
                    "meta.tot_peso_ven",
                    "meta.objetivo_vendas",
                    "meta.tot_val_ven",
                    "meta.percent_atingido"
                )
                ->where(function ($query) use ($idFilial) {
                    is_null($idFilial) ?: $query->where("meta.id_filial", $idFilial);
                })
                ->where(function ($query) use ($idVendedor) {
                    is_null($idVendedor) ?: $query->whereIn("meta.id_vendedor", $idVendedor);
                })
                ->get();

            return $this->service->verificarErro($dados);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function getHead($registros)
    {
        $qtdVendidaTotal = 0;
        $pesoTotal = 0;
        $objetivoTotal = 0;
        $totalVendido = 0;
        $totalPercent = 0;

        foreach ($registros as $vendedor) {

            $qtdVendidaTotal += $vendedor->quantidadeTotalVendida;
            $pesoTotal += $vendedor->pesoTotalVendido;
            $objetivoTotal += number_format($vendedor->objetivo, 2, '.', '');
            $totalVendido += $vendedor->valorTotalVendido;
        }

        if ($objetivoTotal != 0) {
            $totalPercent = 100 / $objetivoTotal * $totalVendido;
        } else {
            $objetivoTotal = 0;
        }

        $resultado = array(
            'totalMetas' => count($registros),
            'qtdTotalVendida' => $qtdVendidaTotal,
            'pesoTotal' => number_format($pesoTotal),
            'objetivoTotal' => number_format($objetivoTotal, 2),
            'valorTotalVendido' => number_format($totalVendido, 2),
            'percentTotal' => number_format($totalPercent, 2)
        );

        return $resultado;
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/vendedor/meta",
     *     summary="Lista as metas dos vendedores e dos supervisores",
     *     description="Lista todas as metas dos vendedores da empresa.",
     *     operationId="lista as metas dos vendedores",
     *     tags={"Metas"},
     *     @OA\Parameter(
     *       name="idFilial",
     *       in="query",
     *       description="ID da filial que detém as metas do vendedores e os vendedores.",
     *       required=false,
     *       @OA\Schema(type="integer", example="1", description="ID da filial")
     *      ),
     *     @OA\Parameter(
     *       name="idVendedor",
     *       in="query",
     *       description="ID dos vendedores.",
     *       required=false,
     *       @OA\Schema(type="integer", example="1", description="ID da filial")
     *      ),
     *     @OA\Parameter(
     *       name="somenteSupervisor",
     *       in="query",
     *       description="Caso seja igual a 1 trará somente os supervisores",
     *       required=false,
     *       @OA\Schema(type="integer", example="1", description="Se for igual a 1 trará somente o supervisor")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de metas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="App\Models\Tenant\Meta"))
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     **/
    public function getVendedoresMeta(Request $request)
    {
        try {
            $resultado['head'] = [];

            if ($request->somenteSupervisor == 1) {

                $resultado['data'] = Vendedor::select(
                    "vendedor.id as idSupervisor",
                    "filial.emp_fan as filial",
                    //Supervisor sem camelCase pois com o php estava trazendo soemte o id e ignoranto o CONCAT 
                    DB::raw("CONCAT(vendedor.id,'-',vendedor.nome) as Supervisor"),
                    "meta.descricao",
                    "meta.tot_qtd_ven as quantidadeTotalVendida",
                    DB::raw("meta.tot_peso_ven as pesoTotalVendido"),
                    "meta.objetivo_vendas as objetivo",
                    DB::raw("FORMAT(meta.tot_val_ven,0,'de_DE') as valorTotalVendido"),
                    DB::raw("meta.percent_atingido as percentualAntigido"),
                )
                    ->where(function ($query) use ($request) {
                        if ($request->somenteSupervisor == 1) $query->where("tipo", SOMENTE_SUPERVISOR);
                        if (!is_null($request->idFilial)) $query->whereIn('meta.id_filial', $request->idFilial);
                        if (!is_null($request->idVendedor)) $query->whereIn('vendedor.id', $request->idVendedor);
                    })
                    ->join('meta', 'vendedor.id', '=', 'meta.id_vendedor')
                    ->join('filial', 'meta.id_filial', '=', 'filial.id')
                    ->get();

                $resultado['head'] = $this->getHead($resultado['data']);

                return $this->service->verificarErro($resultado);
            }

            $resultado['data'] = Vendedor::select(
                "vendedor.id as idVendedor",
                "filial.emp_fan as filial",
                DB::raw("CONCAT(vendedor.id,'-',vendedor.nome) as vendedor"),
                "meta.descricao",
                "meta.tot_qtd_ven as quantidadeTotalVendida",
                "meta.tot_peso_ven as pesoTotalVendido",
                "meta.objetivo_vendas as objetivo",
                "meta.tot_val_ven as valorTotalVendido",
                "meta.percent_atingido as percentualAntigido"
            )
                ->where(function ($query) use ($request) {
                    if ($request->somenteSupervisor == 1) $query->where("tipo", SOMENTE_SUPERVISOR);
                    if (!is_null($request->idFilial)) $query->whereIn('meta.id_filial', $request->idFilial);
                    if (!is_null($request->idVendedor)) $query->whereIn('vendedor.id', $request->idVendedor);
                })
                ->join('meta', 'vendedor.id', '=', 'meta.id_vendedor')
                ->join('filial', 'meta.id_filial', '=', 'filial.id')
                ->get();

            $resultado['head'] = $this->getHead($resultado['data']);

            return $this->service->verificarErro($resultado);


            $resultado['head'] = $this->getHead($resultado['data']);

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function getMetaDetalhesVendedor(Request $request)
    {
        try {
            $resultado['data'] = MetaDetalhe::select(
                "meta_detalhe.id",
                "meta_detalhe.descricao",
                "meta_detalhe.tot_cli_cadastrados as totClientesCadastrados",
                "meta_detalhe.tot_cli_atendidos as totClientesAtendidos",
                "meta_detalhe.percent_tot_cli_atendidos as percentualClientesAtendidos",
                "meta_detalhe.tot_qtd_ven as totQuantidadeVendida",
                "meta_detalhe.tot_peso_ven as totPesoVendido",
                "meta_detalhe.percent_tot_cli_atendidos as percentualClientesAtendidos",
                "meta_detalhe.tot_qtd_ven as totQuatidadeVendida",
                "meta_detalhe.tot_peso_ven as totPesoVendido",
                "meta_detalhe.tot_val_ven as totValorVendido",
                "meta_detalhe.percent_tot_val_ven as percentualTotValorVendido",
                "meta_detalhe.objetivo_vendas as objetivoVendas",
                "meta_detalhe.percent_atingido as percentualAtingido",
                "meta_detalhe.tendencia_vendas as tendenciaVendas",
                "meta_detalhe.percent_tendencia_ven as percentTendenciaVen",
                "meta_detalhe.objetivo_clientes as objetivoClientes",
                "meta_detalhe.numero_cli_falta_atender as numeroCliFaltaAtender",
                "meta_detalhe.ped_a_faturar as pedAFaturar",
                "meta_detalhe.prazo_medio as prazoMedio",
                "meta_detalhe.percent_desconto as percentDesconto",
                "meta_detalhe.tot_desconto as totDesconto"
            )
                ->where(function ($query) use ($request) {
                    if ($request->somenteSupervisor == 1) $query->where("tipo", SOMENTE_SUPERVISOR);
                    if (!is_null($request->idFilial)) $query->whereIn('meta.id_filial', $request->idFilial);
                    if (!is_null($request->idVendedor)) $query->whereIn('vendedor.id', $request->idVendedor);
                })
                ->join('meta', 'meta_detalhe.id_meta', 'meta.id')
                ->join('filial', 'meta.id_filial', 'filial.id')
                ->join('vendedor', 'vendedor.id', 'meta.id_vendedor')
                ->get();

            return $resultado;
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
