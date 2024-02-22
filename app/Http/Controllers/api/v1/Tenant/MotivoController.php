<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Motivo;
use App\Services\BaseService;
use Exception;

/**
 * @OA\Info(
 *     title="API AFV",
 *     version="1.0.0",
 *     description="API interna para integração com o Front-end e API externa para integração com outros sistemas",
 *     @OA\Contact(
 *         email="programacao@sig2000.com.br"
 *     ),
 * )
 */
class MotivoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/motivo",
     *     summary="Lista os motivos",
     *     description="Lista todas as motivos da empresa.",
     *     operationId="lista de motivos",
     *     tags={"Motivos"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de motivos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="App\Models\Tenant\Motivo"))
     *     )
     * )
     **/
    public function index()
    {
        try {

            $motivo = Motivo::select()
                ->get();

            return $this->service->verificarErro($motivo);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
