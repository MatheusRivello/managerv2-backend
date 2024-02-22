<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\Filial;
use App\Services\BaseService;

/**
 * @OA\Get(
 *    path="/api/ws/filial",  
 *    summary="Lista todas as filiais",
 *    description="Lista todas as filiais da empresa.",
 *    operationId="lista de filiais",
 *    tags={"Filial Externa"},
 *     @OA\Parameter(
 *      name="emp_cgc",
 *      in="query",
 *      description="CNPJ da filial",
 *      required=false,
 *      @OA\Schema(type="string", example="1", description="ID da filial")
 *     ),
 *     @OA\Parameter(
 *      name="emp_raz",
 *      in="query",
 *      description="Razão social da filial",
 *      required=false,
 *     @OA\Schema(type="string", example="1", description="Razão social")
 *      ),
 *     @OA\Parameter(
 *      name="emp_fan",
 *      in="query",
 *      description="Nome fantasia da filial",
 *      required=false,
 *     @OA\Schema(type="string", example="1", description="Nome fantasia")
 *      ),
 *     @OA\Response(
 *        response="200",
 *        description="Devolve um array de filiais.",
 *        @OA\JsonContent(type="array", @OA\Items(ref="App\Models\Tenant\Grupo"))
 *     ),
 *     @OA\Response(
 *        response="400",
 *        description="Requisição de forma incorreta."
 *     ),
 *     @OA\Response(
 *        response="401",
 *        description="Não autorizado."
 *     )
 *   )
 * ),
 * 
 * @OA\Post(
 *     path="/api/ws/filial/cadastro",
 *     summary="Cria um novo cadastro de filial(caso já exista um registro com os seguintes campos id,emp_cgc,emp_raz,emp_fan) o registro será atualizado",
 *     tags={"Filial Externa"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="empCgc", type="string"),
 *             @OA\Property(property="empRaz", type="string"),
 *             @OA\Property(property="empFan", type="string"),
 *             @OA\Property(property="empAtiva", type="integer"),
 *             @OA\Property(property="empUf", type="string"),
 *             @OA\Property(property="empCaminhoImg", type="string"),
 *             @OA\Property(property="empUrlImg", type="integer"),
 *             @OA\Property(property="empEmail", type="string"),
 *         )
 *     ),
 *     @OA\Response(response="200", description="Registro salvo"),
 *     @OA\Response(response="400", description="A API irá informar onde se encontra o erro.")
 * )
 * 
 * @OA\Delete(
 *     path="/api/ws/filial/{id}",
 *     summary="Apaga a filial baseado no ID",
 *     description="Apaga a filial baseado no ID",
 *     tags={"Filial Externa"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID da filial",
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
class FilialExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Filial::class;
        $this->filters = ['emp_cgc', 'emp_raz', 'emp_fan'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_FILIAL_EXTERNA;
        $this->firstOrNew = ['id', 'emp_cgc', 'emp_raz', 'emp_fan'];
        $this->fields = [
            'id',
            'emp_cgc',
            'emp_raz',
            'emp_fan',
            'emp_ativa',
            'emp_uf',
            'emp_caminho_img',
            'emp_url_img',
            'emp_email'
        ];
    }
}
