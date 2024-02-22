<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Services\api\Tenant\PrazoDePagamentoService;

class PrazoDePagamentoController extends Controller
{

    private $prazoPagamento;

    public function __construct(PrazoDePagamentoService $prazoPagamento)
    {
        $this->prazoPagamento = $prazoPagamento;
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/prazopagamento",
     *     summary="Lista os prazos de pagamento",
     *     description="Lista todos os prazos de pagamentos da empresa.",
     *     operationId="lista de prazos de pagamento",
     *     tags={"Prazo de pagamentos"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de prazos de pagamentos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="App\Models\Tenant\PrazoPagamento"))
     *     ),
     *     @OA\Response(
     *        response="400",
     *        description="Requisição de forma incorreta."
     *     ),
     *     @OA\Response(
     *        response="401",
     *        description="Não autorizado."
     *     )
     * )
     **/
    public function getAll()
    {
        return $this->prazoPagamento->index();
    }
}
