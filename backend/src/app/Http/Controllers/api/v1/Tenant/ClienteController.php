<?php

namespace App\Http\Controllers\api\v1\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Tenant\ClienteService;

class ClienteController extends Controller
{
    private $clientService;

    public function __construct(ClienteService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @OA\Get(
     *     path="/api/tenant/clientes",
     *     summary="Lista os clientes",
     *     description="Lista todos os clientes da empresa.",
     *     operationId="lista de clientes",
     *     tags={"Clientes"},
     *      @OA\Parameter(
     *       name="idFilial",
     *       in="query",
     *       description="ID da filial que detém os clientes.",
     *       required=false,
     *       @OA\Schema(type="integer", example="1", description="ID da filial")
     *      ),
     *      @OA\Parameter(
     *       name="nome",
     *       in="query",
     *       description="Nome do cliente.",
     *       required=false,
     *       @OA\Schema(type="string", example="1", description="Nome do cliente")
     *      ),
     *      @OA\Parameter(
     *       name="resumido",
     *       in="query",
     *       description="Se passado como 1 trará somente os seguintes campos: id,id_filial,razao_social,nome_fantasia ",
     *       required=false,
     *       @OA\Schema(type="integer", example="1", description="1 ou 0")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de clientes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="App\Models\Tenant\Cliente"))
     *     ),
     *     @OA\Response(
     *        response="400",
     *        description="Requisição feita de forma incorreta."
     *     ),
     *     @OA\Response(
     *        response="401",
     *        description="Não autorizado."
     *     )
     *     
     * )
     **/
    public function findClients(Request $request)
    {
        return $this->clientService->getClientes($request);
    }
}
