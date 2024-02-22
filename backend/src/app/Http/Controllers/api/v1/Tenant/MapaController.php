<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Rastro;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapaController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/mapas/rastrear",
     *     summary="Lista as localizações dos vendedores.",
     *     description="Lista as localizações dos vendedores no mapa, trará 1 array por vendedor filtrado.",
     *     operationId="Lista as localizações dos vendedores no mapa.",
     *     tags={"Mapa"},
     *     @OA\Parameter(
     *         name="data",
     *         in="query",
     *         description="Data a ser filtrada as localizações",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-01-01", description="Dada do filtro")
     *     ),
     *     @OA\Parameter(
     *         name="tipo",
     *         in="query",
     *         description="Se irá agrupar pelo vendedor ou não.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="0 = false, 1 = true")
     *     ),
     *     @OA\Parameter(
     *         name="limite",
     *         in="query",
     *         description="Limite de registros por vendedor",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="Se for igual a 1 trará somente o supervisor")
     *     ),
     *     @OA\Parameter(
     *         name="vendedores",
     *         in="query",
     *         description="Ids dos vendedores a serem filtrados as localizações",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="Ids dos vendedores a serem filtrados")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devolve um array com a localização dos vendedores",
     *         @OA\JsonContent(type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="id_vendedor", type="integer"),
     *                 @OA\Property(property="nome", type="string"),
     *                 @OA\Property(property="data_registro", type="string"),
     *                 @OA\Property(property="hora_registro", type="string"),
     *                 @OA\Property(property="latitude", type="string"),
     *                 @OA\Property(property="longitude", type="string"),
     *                 @OA\Property(property="precisao", type="string"),
     *                 @OA\Property(property="mac", type="string")
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */
    private function retornaRastro($id_vendedores, $data = null, $groupByVendedor = FALSE, $limit = null)
    {
        try {
            $this->service->verificaID($id_vendedores);

            $dados = [];

            foreach ($id_vendedores as $id_vendedor) {

                $query =  Rastro::where("id_vendedor", $id_vendedor)
                    ->join("vendedor", "rastro.id_vendedor", "=", "vendedor.id")
                    ->select(
                        "rastro.id",
                        "rastro.id_vendedor",
                        "vendedor.nome",
                        DB::raw("DATE_FORMAT(rastro.data, '%d-%m-%Y') as 'data_registro'"),
                        DB::raw("TIME_FORMAT(rastro.hora, '%H:%i') as 'hora_registro'"),
                        "rastro.latitude",
                        "rastro.longitude",
                        "rastro.precisao",
                        "rastro.mac"
                    )
                    ->orderby("rastro.hora", "DESC")
                    ->where(function ($query) use ($data) {
                        !isset($data) ?: $query->where("rastro.data", $data);
                    })
                    ->get();

                $newQuery = is_bool($groupByVendedor) && $groupByVendedor ? $query->take($limit) : $query->take(1);

                array_push($dados, $newQuery);
            }

            return $this->service->verificarErro($dados);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function localizar(Request $request)
    {
        // 0 - Última localização
        // 1 -"Localização completa
        return $this->retornaRastro(
            $request->vendedores,
            $request->data,
            isset($request->tipo) && $request->tipo ? TRUE : FALSE,
            $request->limite
        );
    }
}
