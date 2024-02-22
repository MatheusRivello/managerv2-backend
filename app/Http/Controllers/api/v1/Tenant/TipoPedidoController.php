<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\TipoPedido;
use App\Services\api\Tenant\TipoPedidoService;
use App\Services\BaseService;
use Exception;

/**
 * @OA\Get(
 *     path="/api/tenant/tipopedido",
 *     summary="Lista os tipos de pedido",
 *     description="Lista todos os tipos de pedidos ativos da empresa.",
 *     operationId="lista os tipos de pedidos",
 *     tags={"Tipo de pedidos"},
 *     @OA\Response(
 *         response=200,
 *         description="Devolve um array de tipo de pedidos ativos.",
 *         @OA\JsonContent(type="array", @OA\Items(ref="App\Models\Tenant\TipoPedido"))
 *     ),
 *     @OA\Response(
 *        response="401",
 *        description="Não autorizado."
 *     )
 * )
 **/
class TipoPedidoController extends Controller
{
    private $tipoPedidoService;

    function __construct(TipoPedidoService $tipoPedidoService)
    {
        $this->tipoPedidoService = $tipoPedidoService;
    }
    public function getTipoPedidos()
    {
        return $this->tipoPedidoService->index();
    }
}
