<?php

namespace App\Http\Controllers\api\v1\Relatorios;

use App\Http\Controllers\Controller;
use App\Services\Relatorios\VisitaNormal;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/api/relatorios/visita/normal",
 *     summary="Lista o relatório de visitas normal",
 *     description="Lista o relatório de visitas baseado nos filtrados passados.",
 *     operationId="Lista o relatório de visitas",
 *     tags={"Relatórios"},
 *     @OA\Parameter(
 *         name="vendedorIds",
 *         in="query",
 *         description="Ids dos vendedores que devem ser colocados no relatório.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos vendedores")
 *     ),
 *     @OA\Parameter(
 *         name="intervaloData",
 *         in="query",
 *         description="intervalo das datas que o relatório deve conter.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="string", example="2023-01-01", description="array com o intervalo da data")
 *     ),
 *     @OA\Parameter(
 *         name="filialIds",
 *         in="query",
 *         description="Ids das filiais que devem ser referenciadas no relatório.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Parameter(
 *         name="supervisorIds",
 *         in="query",
 *         description="Ids dos supervisores dos vendedores.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos supervisores")
 *     ),
 *     @OA\Parameter(
 *         name="clienteIds",
 *         in="query",
 *         description="Ids dos clientes que devem ser filtrados no relatório.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos clientes")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Ids dos status das visitas que devem ser filtrada no relatório.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Parameter(
 *         name="motivo",
 *         in="query",
 *         description="Ids dos motivos da visita que devem ser filtrada no relatório.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Devolve o relatório",
 *         @OA\JsonContent(
 *             @OA\Property(property="head", type="object",
 *                   @OA\Property(property="totRegistros", type="number"),
 *                   @OA\Property(property="totVendedores", type="number"),  
 *                   @OA\Property(property="totAbertas", type="number"),
 *                   @OA\Property(property="totSemVisitas", type="number"),  
 *                   @OA\Property(property="totSemVendas", type="number"),
 *                   @OA\Property(property="totComVendas", type="number"),  
 *                   @OA\Property(property="totFinalizadas", type="number"),
 *                   @OA\Property(property="totFinalizadoForaRaio", type="number")
 *                 ),   
 *             @OA\Property(property="data", type="array",
 *               @OA\Items(
 *                   @OA\Property(property="id", type="integer"),
 *                   @OA\Property(property="data", type="string"),
 *                   @OA\Property(property="vendedor", type="string"),
 *                   @OA\Property(property="vendedorId", type="integer"),
 *                   @OA\Property(property="ordem", type="integer"),
 *                   @OA\Property(property="cliente", type="string"),
 *                   @OA\Property(property="localVisita", type="string"),
 *                   @OA\Property(property="localRegistrado", type="string"),
 *                   @OA\Property(property="inicio", type="string"),
 *                   @OA\Property(property="fim", type="string"),
 *                   @OA\Property(property="diferenca", type="string"),
 *                   @OA\Property(property="status", type="integer"),
 *                   @OA\Property(property="motivo", type="string"),
 *                   @OA\Property(property="observacao", type="string"),
 *                   @OA\Property(property="agendado_erp", type="integer"),
 *               ),
 *           ), 
 *       ),
 *     
 *     
 *   ),
 *   @OA\Response(
 *        response=401,
 *        description="Não autorizado"
 *     ),
 * )
 **/
class VisitaNormalController extends Controller
{

    private $visitaNormal;

    public function __construct(VisitaNormal $visitaNormal)
    {
        $this->visitaNormal = $visitaNormal;
    }

    public function getAll(Request $request)
    {
        return $this->visitaNormal->getAll($request);
    }
}
