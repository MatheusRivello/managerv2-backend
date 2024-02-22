<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\CercaEletronica;
use App\Models\Central\MotivoCercaEletronica;
use App\Models\Tenant\Pedido;
use App\Models\Tenant\Vendedor;
use App\Services\api\CercaEletronicaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CercaEletronicaController extends Controller
{
    private $service;
    private $usuario;

    public function __construct()
    {
        $this->service = new CercaEletronicaService;
        $this->usuario = $this->service->usuarioLogado();
    }
     /**
     * @OA\Get(
     *     path="/api/geral/cercaeletronica",
     *     summary="Lista as cercas eletrônica",
     *     description="Lista as cercas eletrônica.",
     *     operationId="Lista as cercas eletrônica",
     *     tags={"Cerca Eletrônica"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve as cercas eletrônicas",
     *         @OA\JsonContent(
     *          @OA\Property(property="current_page", type="integer"),
     *          @OA\Property(property = "data", type="array",
     *           @OA\Items(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="fk_empresa", type="integer"),
     *              @OA\Property(property="fk_usuario", type="integer"),
     *              @OA\Property(property="fk_motivo_cerca_eletronica", type="integer"),
     *              @OA\Property(property="dt_cadastro", type="string", format="datetime"),
     *              @OA\Property(property="id_vendedor", type="integer"),
     *              @OA\Property(property="mac", type="string"),
     *              @OA\Property(property="tipo_gerado", type="string"),
     *              @OA\Property(property="observacao", type="string"),
     *           )
     *         )
     *         )
     *     ),
     *       
     *      @OA\Response(response="401", description="Token Expirado.")
     * )
     **/
    public function index()
    {
        $query = CercaEletronica::paginate();

        return $this->service->verificarErro($query);
    }

    /**
     * @OA\Get(
     *     path="/api/geral/cercaeletronica/empresa",
     *     summary="Lista a cerca eletrônica com base na empresa",
     *     description="Lista a cerca eletrônica com base na empresa.",
     *     operationId="Lista a cerca eletrônica com base na empresa",
     *     tags={"Cerca Eletrônica"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de empresas na cerca eletrônica",
     *         @OA\JsonContent(
     *           @OA\Property(property="current_page", type="integer"),
     *           @OA\Property(property="data", type="array",
     *              @OA\Items(
     *                @OA\Property(property="id",type="integer"),
     *                @OA\Property(property="fk_usuario",type="integer"),
     *                @OA\Property(property="fk_motivo_cerca_eletronica",type="integer"),
     *                @OA\Property(property="id_vendedor",type="integer"),
     *                @OA\Property(property="mac",type="string"),
     *                @OA\Property(property="tipo_gerado",type="string"),
     *                @OA\Property(property="observacao",type="string"),
     *                @OA\Property(property="dt_cadastro",type="string"),
     *                @OA\Property(property="usuario",type="object",
     *                      @OA\Property(property="id",type="integer"),  
     *                      @OA\Property(property="nome",type="string"),  
     *                ),
     *                @OA\Property(property="motivo_cerca_eletronica",type="object",
     *                      @OA\Property(property="id",type="integer"),  
     *                      @OA\Property(property="descricao",type="string"),  
     *                ),
     *                @OA\Property(property="vendedor",type="object",
     *                      @OA\Property(property="id",type="integer"),  
     *                      @OA\Property(property="nome",type="string"),  
     *                ),
     *              )),
     *         )
     *     )
     * )
     **/
    public function indexEmpresa()
    {
        try {
            $cerca = CercaEletronica::where('fk_empresa', $this->usuario->fk_empresa)
                ->select(
                    'id',
                    'fk_usuario',
                    'fk_motivo_cerca_eletronica',
                    'id_vendedor',
                    'mac',
                    'tipo_gerado',
                    'token',
                    'observacao',
                    'dt_cadastro',
                )
                ->with(['usuario:id,nome', 'motivo_cerca_eletronica', 'vendedor:id,nome'])
                ->paginate();

            return $this->service->verificarErro($cerca);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function indexMotivo()
    {
        return MotivoCercaEletronica::all();
    }

    /**
     * @OA\Post(
     *    path="/api/geral/cercaeletronica",
     *    summary="Gera um token para o dispositivo",
     *    description="Gera um token para o dispositivo.",
     *    operationId="Gera um token para o dispositivo",
     *    tags={"Cerca Eletrônica"},
     *    @OA\RequestBody(
     *       @OA\JsonContent(
     *         @OA\Property(property="id_motivo", type="integer"),
     *         @OA\Property(property="id_vendedor", type="integer"),
     *       )
     *    ),
     *    @OA\Response(response="200", description="Senha gerada com sucesso.Token:1234567"),
     *    @OA\Response(response="400", description="Token do dispositivo inválido ou vendedor inativo!."),
     *    @OA\Response(response="401", description="Token Expirado.")
     * )
     */
    public function store(Request $request)
    {

        try {
            $this->service->verificarCamposRequest($request, RULE_CERCA_ELETRONICA_CENTRAL);

            $tokenCompleto = $this->service->gerarToken();

            $dispositivo = $this->service->validaTokenDispositivo($request->id_vendedor, $this->usuario->fk_empresa);

            $dataGeradoToken = date("Y-m-d H:i");

            $idMotivo = $request->id_motivo;

            $token = $tokenCompleto["token"] . ($idMotivo < 10 ? "0" . $idMotivo : $idMotivo);

            $cercaEletrica = new CercaEletronica();
            $cercaEletrica->fk_usuario = $this->usuario->id;
            $cercaEletrica->fk_empresa = $this->usuario->fk_empresa;
            $cercaEletrica->fk_motivo_cerca_eletronica = $request->id_motivo;
            $cercaEletrica->dt_cadastro = $dataGeradoToken;
            $cercaEletrica->id_vendedor = $request->id_vendedor;
            $cercaEletrica->token = $token;
            $cercaEletrica->mac = $dispositivo["mac"];
            $cercaEletrica->tipo_gerado = $tokenCompleto["tipoGerado"];
            $cercaEletrica->observacao = isset($request->observacao) ? $request->observacao : null;
            $cercaEletrica->save();

            return response()->json([
                "message" => SENHA_GERADA_SUCESSO,
                "token" => $token
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/geral/cercaeletronica/{id}",
     *     summary="Lista a cerca eletrônica com base no id da mesma",
     *     description="Lista a cerca eletrônica com base no id da mesma",
     *     operationId="listaCercaEletronicaPorId",
     *     tags={"Cerca Eletrônica"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da cerca eletrônica",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de empresas na cerca eletrônica",
     *         @OA\JsonContent(type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="fk_empresa", type="integer"),
     *                 @OA\Property(property="fk_usuario", type="integer"),
     *                 @OA\Property(property="fk_motivo_cerca_eletronica", type="integer"),
     *                 @OA\Property(property="dt_cadastro", type="string"),
     *                 @OA\Property(property="id_vendedor", type="integer"),
     *                 @OA\Property(property="mac", type="string"),
     *                 @OA\Property(property="tipo_gerado", type="string"),
     *                 @OA\Property(property="observacao", type="string"),
     *                 @OA\Property(property="motivo_cerca_eletronica", type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="descricao", type="string")
     *                 ),
     *                 @OA\Property(property="usuario", type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="fk_empresa", type="integer"),
     *                     @OA\Property(property="fk_perfil", type="integer"),
     *                     @OA\Property(property="fk_tipo_empresa", type="integer"),
     *                     @OA\Property(property="id_gerente_supervisor", type="string"),
     *                     @OA\Property(property="nome", type="string"),
     *                     @OA\Property(property="telefone", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="usuario", type="string"),
     *                     @OA\Property(property="senha", type="string"),
     *                     @OA\Property(property="codigo_autenticacao", type="string"),
     *                     @OA\Property(property="tipo_acesso", type="boolean"),
     *                     @OA\Property(property="status", type="boolean"),
     *                     @OA\Property(property="responsavel", type="boolean"),
     *                     @OA\Property(property="codigo_tempo", type="string"),
     *                     @OA\Property(property="codigo_senha", type="string"),
     *                     @OA\Property(property="dt_cadastro", type="string"),
     *                     @OA\Property(property="dt_modificado", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="401", description="Token Expirado.")
     * )
     */
    public function show($id)
    {
        $cerca = CercaEletronica::where('id', $id)->with('motivo_cerca_eletronica', 'usuario')->get();

        return response()->json([$this->service->verificarErro($cerca)], isset($cerca) ? 200 : 404);
    }

    public function countPedLiberadosSenha()
    {
        try {
            $query = Pedido::select(DB::raw('id_vendedor, count(autorizacaoSenha) as total'))
                ->groupBy(['id_vendedor', 'id_filial'])
                ->orderBy('total', 'DESC')
                ->with('vendedor_info')
                ->get();

            return response()->json($query, 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 404);
        }
    }

    public function countVenSolicitaSenha()
    {
        try {
            $query = CercaEletronica::select(DB::raw('id_vendedor, count(id_vendedor) as total'))
                ->where('fk_empresa', $this->usuario->fk_empresa)
                ->groupBy(['id_vendedor', 'fk_empresa'])
                ->orderBy('total', 'DESC')
                ->get();

            foreach ($query as $key => $infoVen) {
                $query[$key]->nome_vendedor = Vendedor::find($infoVen->id_vendedor)->nome;
            }

            return response()->json($query, 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 404);
        }
    }

    public function pedidosLiberadosPorSenha(Request $request)
    {
        try {

            $cerca = Vendedor::select(
                'pedido.id as idPedidoNuvem',
                'central_afv.usuario.usuario',
                DB::raw("CONCAT(vendedor.id,'-',vendedor.nome) as vendedor"),
                'central_afv.cerca_eletronica.token',
                'central_afv.motivo_cerca_eletronica.descricao as motivo',
                'central_afv.cerca_eletronica.observacao',
                'pedido.distanciaCliente',
                DB::raw("DATE_FORMAT(pedido.dt_emissao,'%d/%m/%Y %H:%i') as data"),
            )
                ->join('pedido', 'vendedor.id', '=', 'pedido.id_vendedor')
                ->join('central_afv.cerca_eletronica', 'vendedor.id', '=', 'central_afv.cerca_eletronica.id_vendedor')
                ->join('central_afv.usuario', 'central_afv.cerca_eletronica.fk_usuario', '=', 'central_afv.usuario.id')
                ->join('central_afv.motivo_cerca_eletronica', 'central_afv.cerca_eletronica.fk_motivo_cerca_eletronica', '=', 'central_afv.motivo_cerca_eletronica.id')
                ->where(function ($query) use ($request) {
                    if (!is_null($request->idVendedor)) $query->whereIn("vendedor.id", $request->idVendedor);
                    if (!is_null($request->dataInicial) && !is_null($request->dataFinal)) $query->whereBetween('pedido.dt_emissao', [$request->dataInicial, $request->dataFinal]);
                })
                ->paginate(15);

            return $this->service->verificarErro($cerca);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
