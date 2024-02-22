<?php

namespace App\Http\Controllers\api\v1\Relatorios;

use App\Http\Controllers\Controller;
use App\Services\Relatorios\Analitico;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/api/tenant/visita/vendedor-analitico",
 *     summary="Lista o relatório analítico das visitas dos vendedores",
 *     description="Lista o relatório com detalhes das visitas dos vendedores baseado nos filtrados passados.",
 *     operationId="Lista o relatório analítico",
 *     tags={"Relatórios"},
 *     @OA\Parameter(
 *         name="idVendedor",
 *         in="query",
 *         description="Ids dos vendedores filtrados.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos vendedores")
 *     ),
 *     @OA\Parameter(
 *         name="idCliente",
 *         in="query",
 *         description="Ids dos clientes referenciados aos vendedores.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos clientes")
 *     ),
 *     @OA\Parameter(
 *         name="idFilial",
 *         in="query",
 *         description="Ids das filiais referenciados aos vendedores.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Parameter(
 *         name="idPedidoDispositivo",
 *         in="query",
 *         description="Ids dos pedidos do dispositivo referenciados aos vendedores.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID Ids dos pedidos do dispositivo")
 *     ),
 *     @OA\Parameter(
 *         name="dataInicial",
 *         in="query",
 *         description="Data inicial das visitas a serem filtradas",
 *         required=false,
 *         @OA\Schema(type="string", example="2023-01-01", description="Data inicial das visitas a serem filtradas")
 *     ),
 *     @OA\Parameter(
 *         name="dataFinal",
 *         in="query",
 *         description="Data final das visitas a serem filtradas",
 *         required=false,
 *         @OA\Schema(type="string", example="2023-01-01", description="Data final das visitas a serem filtradas")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Devolve o relatório",
 *         @OA\JsonContent(
 *             @OA\Property(property="head", type="object",
 *                          @OA\Property(property="totRegistros", type="integer"),
 *                          @OA\Property(property="totAbertas", type="integer"),  
 *                          @OA\Property(property="totSemVisitas", type="integer"),
 *                          @OA\Property(property="totSemVendas", type="integer"),  
 *                          @OA\Property(property="totComVendas", type="integer"),
 *                          @OA\Property(property="totFinalizadas", type="integer"),  
 *                          @OA\Property(property="totFinalizadoForaRaio", type="integer"),
 *                          @OA\Property(property="totFinalizadoForaRoteiro", type="integer"),  
 *                 ),   
 *             @OA\Property(property="clientesPorVendedor", type="array", 
 *               @OA\Items(
 *                  @OA\Property(property="vendedor", type="string"),
 *                  @OA\Property(property="vendedorId", type="integer"),
 *                  @OA\Property(property="filiais", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="cliente", type="string"),
 *                          @OA\Property(property="razaoSocial", type="string"),
 *                          @OA\Property(property="visitas", type="array",
 *                              @OA\Items(
 *                                @OA\Property(property="data", type="integer"),
 *                                @OA\Property(property="localVisita", type="string"),
 *                                @OA\Property(property="localRegistrado", type="string"),
 *                                @OA\Property(property="inicio", type="string"),
 *                                @OA\Property(property="fim", type="string"),
 *                                @OA\Property(property="duracao", type="string"),
 *                                @OA\Property(property="status", type="integer"),
 *                                @OA\Property(property="motivo", type="string"),
 *                                @OA\Property(property="observacao", type="string"),
 *                                @OA\Property(property="agendado_erp", type="integer"),
 *                              ),),
 *                     )
 *                   ),
 *                  
 *               ),
 *             
 *         ),
 *     ),
 *     
 * ),
 *          @OA\Response(
 *              response=401,
 *              description="Não autorizado"
 *          )
 * )
 **/

class VendedorAnaliticoController extends Controller
{

    private $vendedorAnalitico;

    public function __construct(Analitico $vendedorAnalitico)
    {
        $this->vendedorAnalitico = $vendedorAnalitico;
    }

    public function getAll(Request $request)
    {
        return $this->vendedorAnalitico->findAll($request);
    }
    public function mapObject(Request $request)
    {
        $object = $this->vendedorAnalitico->findAll($request);
        $arrayOfModifiedObject = [];

        for ($i = 0; $i < count($object['clientesPorVendedor']); $i++) {
            $ModifiedObjects = array(
                'vendedor' => $object['clientesPorVendedor'][$i]->vendedor,
                'vendedorId' => $object['clientesPorVendedor'][$i]->vendedorId,
                'filiais' => $object['clientesPorVendedor'][$i]->clientes,
                'totaisVendedor' => $object['clientesPorVendedor'][$i]->totaisVendedor
            );
            array_push($arrayOfModifiedObject, $ModifiedObjects);
        }

        return [
            'head' => $object['head'],
            'clientesPorVendedor' => $arrayOfModifiedObject
        ];
    }
}
