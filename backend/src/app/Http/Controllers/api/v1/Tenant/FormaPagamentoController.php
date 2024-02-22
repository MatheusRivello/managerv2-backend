<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\FormaPagamento;
use App\Services\BaseService;
use Exception;

class FormaPagamentoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/formapagamento",
     *     summary="Lista as formas de pagamento",
     *     description="Lista todas as formas de pagamento da empresa.",
     *     operationId="lista de formas de pagamento",
     *     tags={"Formas de Pagamento"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de forma de pagamentos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="App\Models\Tenant\FormaPagamento"))
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
    public function getListaFormasPagamento()
    {
        try {
            $resultado = FormaPagamento::select(
                'id',
                'id_retaguarda',
                'descricao'
            )
                ->where('status', 1)
                ->distinct()
                ->get();

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
