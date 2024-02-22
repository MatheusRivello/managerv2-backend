<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\NotaFiscal;
use App\Models\Tenant\NotaFiscalItem;
use App\Services\api\Tenant\NotaFiscalService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotaFiscalController extends Controller
{
    private $service;
    private const STATUS_NOTA_BONIFICADA = 4;
    private const STATUS_NOTA_BONIFICADA_VENDA_PLANO = "F";

    public function __construct()
    {
        $this->service = new NotaFiscalService();
    }
     /**
     * @OA\Get(
     *     path="/api/tenant/nota",
     *     summary="Lista as nota fiscais.",
     *     description="Lista as nota fiscais.",
     *     operationId="Lista as nota fiscais.",
     *     tags={"Nota Fiscal"},
     *     @OA\Parameter(
     *         name="filial",
     *         in="query",
     *         description="Filial no qual se encontram as notas",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID da filial")
     *     ),
     *     @OA\Parameter(
     *         name="dataInicio",
     *         in="query",
     *         description="Data inicial das notas fiscais.",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-01-01", description="Data inicial da nota")
     *     ),
     *      @OA\Parameter(
     *         name="dataFim",
     *         in="query",
     *         description="Data final das notas fiscais.",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-01-01", description="Data final da nota")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limite de registros das notas fiscais",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="Limite de notas")
     *     ),
     *     @OA\Parameter(
     *         name="somarNotasBonificadas",
     *         in="query",
     *         description="Se irá somar as notas notas fiscais bonificadas",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="0=não, 1=sim")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array das notas fiscais",
     *         @OA\JsonContent(
     *             @OA\Property(property="totValNotasBonificadas", type="integer"),   
     *             @OA\Property(property="notas", type="array", @OA\Items(ref="App\Models\Tenant\NotaFiscal")),
     *         ),
     *     ), 
     *     @OA\Response(
     *         response=401,
     *         description="Token expirado"
     *     )
     * )
     */
    public function getNotas(Request $request)
    {
        try {
            $totBonificadas = 0;

            $resultado['totValNotasBonificadas'] = [];
            $resultado['notas'] = [];

            $cliente = (!$request->descricaoCliente || $request->descricaoCliente == RAZAO_SOCIAL_CLIENTE) ? "cliente.razao_social" : "cliente.nome_fantasia";

            $resultado['notas'] = NotaFiscal::select(
                "nota_fiscal.id",
                "filial.id AS id_filial",
                DB::raw("IF(filial.emp_raz IS NULL OR filial.emp_raz = '', filial.emp_fan, filial.emp_raz) AS filial,
                    " . $cliente . " AS cliente"),
                "cliente.nome_fantasia",
                DB::raw("CONCAT(vendedor.id,'-',vendedor.nome) AS vendedor"),
                "nota_fiscal.ped_num",
                "nota_fiscal.nfs_doc",
                DB::raw("IF(nota_fiscal.nfs_custo IS null, 0, nota_fiscal.nfs_custo) as nfs_custo"),
                DB::raw("IF(nota_fiscal.nfs_custo IS null , 0, IF (nota_fiscal.nfs_custo > 0, (100 - ((nota_fiscal.nfs_custo / nota_fiscal.ped_total) *100)), 0)) AS margem_ped"),
                DB::raw("IF(nota_fiscal.nfs_custo IS null , 0, IF (nota_fiscal.nfs_custo > 0, ((nota_fiscal.ped_total / nota_fiscal.nfs_custo) * 100) -100, 0)) AS markup_ped"),
                "nota_fiscal.ped_total",
                DB::raw("IF(nota_fiscal.nfs_custo IS null , 0, IF (nota_fiscal.nfs_custo > 0, (100 - ((nota_fiscal.nfs_custo / nota_fiscal.nfs_valbrut) *100)), 0)) AS margem_nfs"),
                DB::raw("IF(nota_fiscal.nfs_custo IS null , 0, IF (nota_fiscal.nfs_custo > 0, ((nota_fiscal.nfs_valbrut / nota_fiscal.nfs_custo) * 100) -100, 0)) AS markup_nfs"),
                "nota_fiscal.nfs_valbrut",
                DB::raw("DATE_FORMAT(nota_fiscal.ped_emissao,'%d-%m-%Y') AS pedido_emissao"),
                DB::raw("DATE_FORMAT(nota_fiscal.nfs_emissao,'%d-%m-%Y') AS nota_emissao"),
                "nota_fiscal.nfs_status",
                "nota_fiscal.nfs_tipo"
            )
                ->join("cliente", "nota_fiscal.id_cliente", "=", "cliente.id")
                ->join("filial", "nota_fiscal.id_filial", "=", "filial.id")
                ->join("vendedor", "nota_fiscal.id_vendedor", "=", "vendedor.id")
                ->where(function ($filtro) use ($request) {
                    if (!is_null($request->filial)) $filtro->whereIn("nota_fiscal.id_filial", $request->filial);
                    if (!is_null($request->vendedor)) $filtro->whereIn("nota_fiscal.id_vendedor", $request->vendedor);
                    if (!is_null($request->cliente)) $filtro->whereIn("nota_fiscal.id_cliente", $request->cliente);
                    if (!is_null($request->statusNota)) $filtro->whereIn("nota_fiscal.nfs_status", $request->statusNota);

                    if (!is_null($request->dataInicio) && !is_null($request->dataFim)) {
                        if ($request->tipoData == DATA_PEDIDO) {
                            $filtro->where([["nota_fiscal.ped_emissao", ">=", $request->dataInicio], ["nota_fiscal.ped_emissao", "<=", $request->dataFim]]);
                        } else {
                            $filtro->where([["nota_fiscal.nfs_emissao", ">=", $request->dataInicio], ["nota_fiscal.nfs_emissao", "<=", $request->dataFim]]);
                        }
                    }
                })
                ->limit($request->limit)
                ->get();


            if ($request->somarNotasBonificadas == 1) {
                for ($i = 0; $i < count($resultado['notas']); $i++) {

                    if ($resultado['notas'][$i]->nfs_tipo == self::STATUS_NOTA_BONIFICADA || $resultado['notas'][$i]->nfs_tipo == self::STATUS_NOTA_BONIFICADA_VENDA_PLANO) {
                        $totBonificadas += $resultado['notas'][$i]->nfs_valbrut;
                    }
                }
            }

            $resultado['totValNotasBonificadas'] = $totBonificadas;

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/nota/{id}",
     *     summary="Lista a nota fiscal e seus itens com base no filtro.",
     *     description="Lista a nota fiscal e seus itens com base no filtro.",
     *     operationId="Lista a nota fiscal e seus itens com base no filtro.",
     *     tags={"Nota Fiscal"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID da nota fiscal.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID da nota fiscal")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve a nota fiscal e um array com os itens da mesma",
     *         @OA\JsonContent( 
     *             @OA\Property(property="cabecalho", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="id_filial", type="integer"),
     *                 @OA\Property(property="filial", type="string"),  
     *                 @OA\Property(property="clienteRazaoSocial", type="string"),  
     *                 @OA\Property(property="clienteNomeFantasia", type="string"),  
     *                 @OA\Property(property="vendedor", type="string"),  
     *                 @OA\Property(property="ped_num", type="integer"),  
     *                 @OA\Property(property="nfs_doc", type="string"),  
     *                 @OA\Property(property="nfs_serie", type="string"),  
     *                 @OA\Property(property="ped_total", type="integer"),  
     *                 @OA\Property(property="nfs_valbrut", type="integer"),  
     *                 @OA\Property(property="ped_emissao", type="string"),  
     *                 @OA\Property(property="nfs_emissao", type="string"),
     *                 @OA\Property(property="prazo_pagamento", type="string"),
     *                 @OA\Property(property="nfs_status", type="integer"),
     *                 @OA\Property(property="observacao", type="string")    
     *             ),
     *             @OA\Property(property="itens", type="array",@OA\items(
     *                 @OA\Property(property="descricao_produto", type="string"),
     *                 @OA\Property(property="nfs_unitario", type="integer"),
     *                 @OA\Property(property="nfs_desconto", type="integer"),  
     *                 @OA\Property(property="ped_qtd", type="integer"),  
     *                 @OA\Property(property="nfs_qtd", type="integer"),  
     *                 @OA\Property(property="pedido_total", type="string"),  
     *                 @OA\Property(property="nota_total", type="string"),  
     *                 @OA\Property(property="nfs_status", type="integer")  
     *             ))  
     *         )
     *     ), 
     *     @OA\Response(
     *         response=401,
     *         description="Token expirado"
     *     )
     * )
     */
    public function getDetalheNota($id)
    {
        try {
            $cabecalho = NotaFiscal::select(
                "nota_fiscal.id",
                "filial.id AS id_filial",
                DB::raw("IF(filial.emp_raz IS NULL OR filial.emp_raz = '', filial.emp_fan, filial.emp_raz) AS filial"),
                "cliente.razao_social AS clienteRazaoSocial",
                "cliente.nome_fantasia AS clienteNomeFantasia",
                DB::raw("CONCAT(vendedor.id,'-',vendedor.nome) AS vendedor"),
                "nota_fiscal.ped_num",
                "nota_fiscal.nfs_doc",
                "nota_fiscal.nfs_serie",
                "nota_fiscal.ped_total",
                "nota_fiscal.nfs_valbrut",
                DB::raw("DATE_FORMAT(nota_fiscal.ped_emissao,'%d-%m-%Y') AS ped_emissao"),
                DB::raw("DATE_FORMAT(nota_fiscal.nfs_emissao,'%d-%m-%Y') AS nfs_emissao"),
                DB::raw("CONCAT(prazo_pagamento.id_retaguarda,'-',prazo_pagamento.descricao) AS prazo_pagamento"),
                "nota_fiscal.nfs_status",
                "nota_fiscal.observacao"
            )
                ->join("cliente", "nota_fiscal.id_cliente", "=", "cliente.id")
                ->join("filial", "nota_fiscal.id_filial", "=", "filial.id")
                ->join("vendedor", "nota_fiscal.id_vendedor", "=", "vendedor.id")
                ->join("prazo_pagamento", "prazo_pagamento.id_retaguarda", "=", "nota_fiscal.prazo_pag")
                ->where('nota_fiscal.id', $id)
                ->first();

            if (!isset($cabecalho)) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 404);
            }

            $resultado["cabecalho"] = $cabecalho;

            $itens = NotaFiscalItem::select(
                DB::raw("CONCAT(produto.id_retaguarda,' .: ',UPPER(produto.descricao)) AS descricao_produto"),
                "nota_fiscal_item.nfs_unitario",
                "nota_fiscal_item.nfs_desconto",
                "nota_fiscal_item.ped_qtd",
                "nota_fiscal_item.nfs_qtd",
                DB::raw("FORMAT(nota_fiscal_item.ped_total,2,'de_DE') AS pedido_total"),
                DB::raw("FORMAT(nota_fiscal_item.nfs_total,2,'de_DE') AS nota_total"),
                "nota_fiscal_item.nfs_status"
            )
                ->join("produto", "nota_fiscal_item.id_produto", "=", "produto.id")
                ->where('nota_fiscal_item.ped_num', $cabecalho->ped_num)
                ->where("nota_fiscal_item.id_filial", $cabecalho->id_filial)
                ->where(function ($filtro) use ($cabecalho) {
                    if ($cabecalho->nfs_status == PARCIALMENTE_ATENDIDO_NOTA) {
                        $filtro->whereIn("nota_fiscal_item.nfs_status", [1, 2, 3]);
                    } else {
                        $filtro->where("nota_fiscal_item.nfs_doc", $cabecalho->nfs_doc);
                        $filtro->where("nota_fiscal_item.nfs_serie", $cabecalho->nfs_serie);
                    }
                })
                ->get();

            $resultado["itens"] = $itens;

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tenant/nota/margem/markup",
     *     summary="Lista as margens markup",
     *     description="Lista todas as margens markup da empresa.",
     *     operationId="Lista as margens markups",
     *     tags={"Nota Fiscal"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de margens markups",
     *         @OA\JsonContent( 
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array",@OA\Items(
     *                @OA\Property(property="descricao", type="string"),
     *                @OA\Property(property="nfs_custo", type="integer"),
     *                @OA\Property(property="nfs_rentabilidade", type="string"),  
     *                @OA\Property(property="nfs_margem", type="string"),  
     *                @OA\Property(property="nfs_markup", type="string"),  
     *             ))  
     *         )
     *      ), 
     *     @OA\Response(
     *       response=401,
     *       description="Token expirado"
     *     )
     *      
     * )
     **/
    public function getMargemMarkup(Request $request)
    {
        try {
            $opcoesTipoRetorno = $this->service->getMargemMarkup($request);

            return $this->service->verificarErro($opcoesTipoRetorno);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
