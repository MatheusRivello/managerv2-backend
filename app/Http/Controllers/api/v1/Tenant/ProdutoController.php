<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Produto;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }

    /**
     * @OA\Get(
     *     path="/api/tenant/produto",
     *     summary="Lista os produtos.",
     *     description="Lista todos os produtos da filial.",
     *     operationId="Lista os produtos",
     *     tags={"Produtos"},
     *      @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página de produtos que deverá ser apresentada.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="Página de produtos")
     *     ),
     *      @OA\Parameter(
     *         name="filial",
     *         in="query",
     *         description="Filial no qual se encontram os produtos.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID da filial")
     *     ),
     *      @OA\Parameter(
     *         name="descricao",
     *         in="query",
     *         description="Descrição do produto a ser filtrado.",
     *         required=false,
     *         @OA\Schema(type="string", example="Bala", description="Descrição do produto")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status do produto na filial: 1-Normal,2-Suspenso,3-Lançamento,4-Desconto,5-Promoção,6-Fora de linha,7-Sem movimentação.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de produtos",
     *         @OA\JsonContent( 
     *             @OA\Property(property="current_page" , type="integer"),
     *             @OA\Property(property="data" , type="array", @OA\Items(
     *                  @OA\Property(property="filial" , type="string"),            
     *                  @OA\Property(property="id" , type="integer"),           
     *                  @OA\Property(property="id_retaguarda" , type="string"),            
     *                  @OA\Property(property="descricao" , type="string"),            
     *                  @OA\Property(property="unidvenda" , type="string"),            
     *                  @OA\Property(property="status_desc" , type="string"),            
     *               ),
     *                          
     *             ),
     *         ),    
     *      ), 
     *     @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *     )
     *      
     * )
     **/
    public function getListaProdutos(Request $request)
    {
        try {
            $resultado = Produto::select(
                "filial.emp_raz AS filial",
                "produto.id",
                "produto.id_retaguarda",
                DB::raw("CONCAT(produto.id_retaguarda,' .: ',produto.descricao) as descricao"),
                "produto.unidvenda",
                "status_produto.descricao as status_desc"
            )
                ->join("status_produto", "produto.status", "=", "status_produto.id")
                ->join("filial", "produto.id_filial", "=", "filial.id")
                ->where(function ($query) use ($request) {
                    if (!is_null($request->filial)) $query->whereIn("produto.id_filial", $request->filial);
                    if (!is_null($request->idRetaguarda)) $query->where("produto.id_retaguarda", 'like', '%' . $request->idRetaguarda . '%');
                    if (!is_null($request->descricao)) $query->where("produto.descricao", 'LIKE', "%{$request->descricao}%");
                    if (!is_null($request->status)) $query->where("produto.status", 'LIKE', "%{$request->status}%");
                })
                ->distinct()
                ->paginate(10);

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
