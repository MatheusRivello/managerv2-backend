<?php

namespace App\Http\Controllers\api\v1\Relatorios;

use App\Http\Controllers\Controller;
use App\Services\Relatorios\VisitaEfetuadas;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/api/relatorios/visita/efetuadas",
 *     summary="Lista o relatório de visitas efetuadas.",
 *     description="Lista o relatório de visitas efetuadas.",
 *     operationId="Lista o relatório de visitas efetuadas",
 *     tags={"Relatórios"},
 *     @OA\Parameter(
 *         name="idVendedor",
 *         in="query",
 *         description="Ids dos vendedores que devem ser colocados no relatório(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos vendedores")
 *     ),
 *     @OA\Parameter(
 *         name="idFilial",
 *         in="query",
 *         description="Ids das filiais que devem ser referenciadas no relatório(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Parameter(
 *         name="idCliente",
 *         in="query",
 *         description="Ids dos clientes dos vendedores(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos supervisores")
 *     ),
 *     @OA\Parameter(
 *         name="idPedidoDispositivo",
 *         in="query",
 *         description="Ids dos dispositivos no quais foram feitos os pedidos da visita que devem ser filtrada no relatório(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Parameter(
 *         name="intervaloData",
 *         in="query",
 *         description="Intervalo das visitas(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Devolve o relatório",
 *         @OA\JsonContent(
 *             @OA\Property(property="head", type="object",
 *                   @OA\Property(property="totComVisitas", type="integer"),
 *                   @OA\Property(property="totSemVisitas", type="integer"),  
 *                   @OA\Property(property="totAgendadasConsolidados", type="integer"),
 *                   @OA\Property(property="totEfetuadasDetalhados", type="integer"),
 *                   @OA\Property(property="totEfetuadasConsolidados", type="integer")
 *                 ),   
 *             @OA\Property(property="clientesComVisitas", type="array",
 *               @OA\Items(
 *                   @OA\Property(property="id", type="integer"),
 *                   @OA\Property(property="cod", type="string"),
 *                   @OA\Property(property="razaoSocial", type="string"),
 *                   @OA\Property(property="agendadas", type="integer"),
 *                   @OA\Property(property="efetuadas", type="integer")
 *               ),
 *           ), 
 *             @OA\Property(property="clientesSemVisitas", type="array",
 *               @OA\Items(
 *                   @OA\Property(property="id", type="integer"),
 *                   @OA\Property(property="cod", type="string"),
 *                   @OA\Property(property="razaoSocial", type="string"),
 *               ),
 *           ), 
 *       ), 
 *   ),
 *   @OA\Response(
 *        response=401,
 *        description="Não autorizado"
 *     ),
 *   ),
 * )
 **/
class VisitaEfetuadasController extends Controller
{

    private $visitaEfetuada;

    public function __construct(VisitaEfetuadas $visitaEfetuada)
    {
        $this->visitaEfetuada = $visitaEfetuada;
    }

    public function getAll(Request $request)
    {
        return $this->visitaEfetuada->findAll($request);
    }
}
