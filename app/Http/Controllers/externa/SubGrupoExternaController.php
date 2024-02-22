<?php

namespace App\Http\Controllers\externa;


use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Subgrupo;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

/**
 * @OA\Get(
 *    path="/api/ws/subgrupo",  
 *    summary="Lista os subgrupos de produtos",
 *    description="Lista todos os subgrupos de produtos da empresa.",
 *    operationId="lista de subgrupo de produtos",
 *    tags={"Produto Externa"},
 *     @OA\Parameter(
 *      name="id_filial",
 *      in="query",
 *      description="ID da filial que detém os subgrupos.",
 *      required=false,
 *      @OA\Schema(type="integer", example="1", description="ID da filial")
 *     ),
 *     @OA\Parameter(
 *      name="id_retaguarda",
 *      in="query",
 *      description="Id gerado no ERP",
 *      required=false,
 *     @OA\Schema(type="integer", example="1", description="1 ou 0")
 *      ),
 *     @OA\Parameter(
 *      name="id_grupo",
 *      in="query",
 *      description="Id do grupo de produtos a que pertence o subgrupo",
 *      required=false,
 *     @OA\Schema(type="integer", example="1", description="1 ou 0")
 *      ),
 *      @OA\Parameter(
 *      name="status",
 *      in="query",
 *      description="Status do subgrupo de produto",
 *      required=false,
 *     @OA\Schema(type="integer", example="1", description="Status do cliente")
 *      ),
 *     @OA\Response(
 *        response=200,
 *        description="Devolve um array de subgrupo de produtos",
 *        @OA\JsonContent(type="array", @OA\Items(ref="App\Models\Tenant\Subgrupo"))
 *     )
 * ),
 * 
 * @OA\Post(
 *     path="/api/ws/subgrupo/cadastro",
 *     summary="Cria um novo cadastro de subgrupo(caso já exista um registro com os seguintes campos id_filial, id_retaguarda,status), o registro será atualizado",
 *     tags={"Produto Externa"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="idFilial", type="integer"),
 *             @OA\Property(property="idRetaguarda", type="string"),
 *             @OA\Property(property="subGrupoDesc", type="string"),
 *             @OA\Property(property="desctoMax", type="double"),
 *             @OA\Property(property="status", type="integer")
 *         )
 *     ),
 *     @OA\Response(response="200", description="Registro salvo"),
 *     @OA\Response(response="400", description="A API irá informar onde se encontra o erro.")
 * )
 * 
 * @OA\Delete(
 *     path="/api/ws/subgrupo/{id}",
 *     summary="Apaga o subgrupo de produto baseado no ID do mesmo",
 *     description="Apaga o subgrupo de produto baseado no ID do mesmo",
 *     tags={"Produto Externa"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do subgrupo de produto",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Registro excluido com sucesso"
 *     ),
 *     @OA\Response(
 *         response="406",
 *         description= "Este registro não pode ser excluído. Existem registros atrelados a este ID."
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description= "Este ID não foi encontrado"
 *     )
 *     
 * )
 */
class SubGrupoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Subgrupo::class;
        $this->filters = ['id_filial', 'id_retaguarda', 'id_grupo', 'status'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_SUBGRUPO_EXTERNA;
        $this->firstOrNew = ['id_filial', 'id_retaguarda', 'id_grupo', 'status'];
        $this->fields = [
            'id_filial',
            'id_retaguarda',
            'id_grupo',
            'subgrupo_desc',
            'descto_max',
            'status'
        ];
    }
    public function storeSubGrupo(Request $request)
    {   
        try{
            $this->service->verificarCamposRequest($request,RULE_SUBGRUPO_EXTERNA);
            $registro = Subgrupo::firstornew(['id_filial' => $request->idFilial, 'id_retaguarda' => $request->idRetaguarda, 'id_grupo' => $request->idGrupo]);
            $registro->id_filial = $request->idFilial;
            $registro->id_retaguarda = $request->idRetaguarda;
            $registro->id_grupo = $request->idGrupo;
            $registro->subgrupo_desc = $request->subGrupoDesc;
            $registro->descto_max = $request->desctoMax;
            $registro->status = $request->status;
            $registro->save();
            return response()->json(["message:" => REGISTRO_SALVO], 200);
        }
        catch(Exception $exception){
            return response()->json(['error' => true, 'message' => $exception->getMessage()],500);
        }
    }
}
