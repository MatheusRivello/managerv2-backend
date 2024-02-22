<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Seguro;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class SeguroController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/seguros",
     *     summary="Lista os seguros",
     *     description="Lista os seguros.",
     *     operationId="Lista os seguros",
     *     tags={"Seguros"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de seguros.",
     *         @OA\JsonContent(type="array",@OA\Items(ref="App\Models\Tenant\Seguro"))    
     *      ), 
     *     @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *     ),
     *     @OA\Response(
     *       response=403,
     *       description="Token expirado"
     *     )
     *      
     * )
     **/
    public function index()
    {
        try {
            return $this->service->verificarErro(Seguro::all());
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tenant/seguros/{id}",
     *     summary="Lista o seguro com base no ID",
     *     description="Lista o seguro com base no ID.",
     *     operationId="Lista o seguro com base no ID",
     *     tags={"Seguros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id do Seguro",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID do titulo.")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve o seguro filtrado.",
     *         @OA\JsonContent(type="object",ref="App\Models\Tenant\Seguro")    
     *      ), 
     *     @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *     ),
     *     @OA\Response(
     *       response=403,
     *       description="Token expirado"
     *     )
     *      
     * )
     **/
    public function show($id)
    {
        try {
            return $this->service->verificarErro(Seguro::where('id', $id)->get());
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
    /** @OA\Post(
     *     path="/api/tenant/seguros",
     *     summary="Cria um novo cadastro de seguro(caso já exista um registro com os seguintes campos id) o registro será atualizado",
     *     tags={"Seguros"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="valor", type="integer"),
     *             @OA\Property(property="uf", type="string"),
     *             @OA\Property(property="dt_modificado", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Registro salvo"),
     *     @OA\Response(response="400", description="A API irá informar onde se encontra o erro."),
     *     @OA\Response(response=403,description="Token expirado")
     * )
     * 
     */
    public function store(Request $request)
    {
        try {
            $newRequest = array_replace($request->all(), ['uf' => strtoupper($request->uf)]);

            $this->service->verificarCamposRequest($newRequest, RULE_SEGURO_TENANT, $request->id);

            $seguro = Seguro::firstOrNew(['id' => $request->id]);
            $seguro->valor = $request->valor;
            $seguro->uf = $request->uf;
            $seguro->dt_modificado = date("Y-m-d H:i:s");
            $seguro->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/tenant/seguros",
     *     tags={"Seguros"},
     *     summary="Atualiza seguros",
     *     description="Atualiza os seguros passados no ID do corpo da requisição.",
     *     operationId="atualiza seguros",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(type="array",@OA\Items(ref="App\Models\Tenant\Seguro"))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro modificado com sucesso",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requisição feita de forma incorreta",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     @OA\Response(
     *       response=403,
     *       description="Token expirado"
     *     )
     * )
     */
    public function update(Request $request)
    {
        try {
            $atributosRequest = $request->all();
            $arrayDeAtualizacao = array_chunk($atributosRequest, 1);

            foreach ($arrayDeAtualizacao as $key => $value) {
                $registro = Seguro::where('id', '=', $value[0]['id'])->get();
                $registro->each->update(['valor' => $value[0]['valor'], 'uf' => $value[0]['uf'], 'dt_modificado' => date("Y-m-d H:i:s")]);
            }
            return response()->json(['message' => "Registro modificado"], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    /** @OA\Delete(
     *     path="/api/tenant/seguros/{id}",
     *     summary="Apaga o seguro baseado no ID",
     *     description="Apaga o seguro baseado no ID",
     *     tags={"Seguros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do seguro",
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
    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 409);
            }

            $seguro = Seguro::find($id);

            if (!isset($seguro)) {
                throw new Exception(ID_NAO_ENCONTRADO, 404);
            }

            if ($seguro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
