<?php

namespace App\Http\Controllers\api\v1\Relatorios;

use App\Http\Controllers\Controller;
use App\Services\Relatorios\VendedorCarteiraCliente;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/api/relatorios/visita/vendedor-carteira",
 *     summary="Lista o relatório de visitas baseado na carteira de clientes do vendedor",
 *     description="Lista o relatório de visitas baseado na carteira de clientes do vendedor.",
 *     operationId="Lista o relatório de visitas baseado na carteira de clientes do vendedor",
 *     tags={"Relatórios"},
 *     @OA\Parameter(
 *         name="idVendedor",
 *         in="query",
 *         description="Ids dos vendedores que devem ser colocados no relatório.(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos vendedores")
 *     ),
 *     @OA\Parameter(
 *         name="idFilial",
 *         in="query",
 *         description="Ids das filiais que devem ser referenciadas no relatório.(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Parameter(
 *         name="IdCliente",
 *         in="query",
 *         description="Ids dos clientes dos vendedores.(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos supervisores")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Ids dos status das visitas que devem ser filtrada no relatório.(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Parameter(
 *         name="motivo",
 *         in="query",
 *         description="Ids dos motivos da visita que devem ser filtrada no relatório.(obs:passar como array).",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Devolve o relatório",
 *         @OA\JsonContent(
 *             @OA\Property(property="head", type="object",
 *                   @OA\Property(property="totRegistros", type="number"),
 *                   @OA\Property(property="totEfetuadas", type="number"),  
 *                   @OA\Property(property="totAgendadas", type="number"),
 *                   @OA\Property(property="totVendedores", type="number")
 *                 ),   
 *             @OA\Property(property="data", type="array",
 *               @OA\Items(
 *                   @OA\Property(property="id", type="integer"),
 *                   @OA\Property(property="idVendedor", type="integer"),
 *                   @OA\Property(property="vendedor", type="string"),
 *                   @OA\Property(property="filial", type="string"),
 *                   @OA\Property(property="data", type="string"),
 *                   @OA\Property(property="tempoTotal", type="string"),
 *                   @OA\Property(property="tempoMedio", type="string"),
 *                   @OA\Property(property="carteira", type="object",
 *                              @OA\Property(property="carteiraClientes", type="integer"),
 *                               ),
 *                   @OA\Property(property="agendadas", type="integer"),
 *                   @OA\Property(property="efetuadas", type="integer")
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
class VendedorCarteiraClienteController extends Controller {

    private $vendedorCarteiraCliente;

    public function __construct(VendedorCarteiraCliente $vendedorCarteiraCliente)
    {
        $this->vendedorCarteiraCliente = $vendedorCarteiraCliente;
    }

    public function getAll(Request $request)
    {
        return $this->vendedorCarteiraCliente->findAll($request);
    }
}