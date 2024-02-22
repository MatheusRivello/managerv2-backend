<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\Grupo;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

/**
 * @OA\Get(
 *    path="/api/ws/grupo",  
 *    summary="Lista os grupos de produtos",
 *    description="Lista todos os grupos de produtos da empresa.",
 *    operationId="lista de grupo de produtos",
 *    tags={"Produto Externa"},
 *     @OA\Parameter(
 *      name="id_filial",
 *      in="query",
 *      description="ID da filial que detém os grupos de produtos.",
 *      required=false,
 *      @OA\Schema(type="integer", example="1", description="ID da filial")
 *     ),
 *     @OA\Parameter(
 *      name="status",
 *      in="query",
 *      description="Status do grupo de produto",
 *      required=false,
 *     @OA\Schema(type="integer", example="1", description="Status do grupo")
 *      ),
 *     @OA\Parameter(
 *      name="id_retaguarda",
 *      in="query",
 *      description="Id gerado no ERP",
 *      required=false,
 *     @OA\Schema(type="integer", example="1", description="1 ou 0")
 *      ),
 *     @OA\Response(
 *        response=200,
 *        description="Devolve um array de grupo de produtos",
 *        @OA\JsonContent(type="array", @OA\Items(ref="App\Models\Tenant\Grupo"))
 *     )
 * ),
 * 
 * @OA\Post(
 *     path="/api/ws/grupo/cadastro",
 *     summary="Cria um novo cadastro de grupo(caso já exista um registro com os seguintes campos id_filial, id_retaguarda,status), o registro será atualizado",
 *     tags={"Produto Externa"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="idFilial", type="integer"),
 *             @OA\Property(property="idRetaguarda", type="string"),
 *             @OA\Property(property="grupoDesc", type="string"),
 *             @OA\Property(property="desctoMax", type="integer"),
 *             @OA\Property(property="status", type="integer"),
 *         )
 *     ),
 *     @OA\Response(response="200", description="Registro salvo"),
 *     @OA\Response(response="400", description="A API irá informar onde se encontra o erro.")
 * )
 * 
 * @OA\Delete(
 *     path="/api/ws/grupo/{id}",
 *     summary="Apaga o grupo de produto baseado no ID",
 *     description="Apaga o grupo de produto baseado no ID",
 *     tags={"Produto Externa"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do grupo de produto",
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
class GrupoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Grupo::class;
        $this->tabela = 'grupo';
        $this->modelComBarra = '\Grupo';
        $this->filters = ['id', 'status', 'id_retaguarda', 'id_filial'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_GRUPO_EXTERNA;
        $this->firstOrNew = ['id_filial', 'id_retaguarda', 'status'];
        $this->fields = [
            'id_filial',
            'id_retaguarda',
            'grupo_desc',
            'descto_max',
            'status'
        ];
    }

    public function storeGrupo(Request $request)
    {
        try {
            $where = ['id_filial' => $request->idFilial, 'id_retaguarda' => $request->idRetaguarda];
            $this->destroyWhere($where);
            return $this->storePersonalizado($request);
        } catch (Exception $exception) {
            return response()->json(['error:' => true, "message:" => $exception->getMessage()], $exception->getCode());
        }
    }
}
