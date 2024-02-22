<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\ConfiguracaoDispositivo;
use App\Models\Central\Dispositivo;
use App\Models\Central\Horario;
use App\Models\Central\HorarioUtilizacaoDispositivo;
use App\Models\Central\VersaoApp;
use App\Models\Central\VwQtdLicencasEmpresa;
use App\Services\api\DispositivoService;
use Exception;
use Illuminate\Http\Request;

class DispositivoController extends Controller
{
    const LICENCA_AUTENTICADA = 2;
    const DISPOSITIVO_ATIVO = 1;
    const DISPOSITIVO_INATIVO = 0;
    private $service;
    private $usuario;

    public function __construct()
    {
        $this->service = new DispositivoService;
        $this->usuario = $this->service->usuarioLogado();
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/dispositivo",
     *     summary="Lista os dispositivos cadastrados",
     *     description="Lista os dispositivos cadastrados.",
     *     operationId="Lista os dispositivos cadastrados.",
     *     tags={"Dispositivo"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve os dispositivos cadastrados",
     *         @OA\JsonContent(
     *             @OA\Property(property="cabecalho", type="object",
     *                          @OA\Property(property="qtdDispositivosCadastrados", type="integer"),
     *                          @OA\Property(property="qtdDispositivosAtivos", type="integer"),  
     *                          @OA\Property(property="qtdDispositivosInativos", type="integer"),
     *                          @OA\Property(property="versaoApp", type="string"),  
     *                          @OA\Property(property="qtd_contratado", type="integer")  
     *                 ),   
     *             @OA\Property(property="dispositivos", type="array", 
     *               @OA\Items(
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="fk_empresa", type="integer"),
     *                  @OA\Property(property="marca", type="string"),
     *                  @OA\Property(property="mac", type="string"),
     *                  @OA\Property(property="modelo", type="string"),
     *                  @OA\Property(property="versaoApp", type="string"),
     *                  @OA\Property(property="versao_android", type="string"),
     *                  @OA\Property(property="imei", type="string"),
     *                  @OA\Property(property="licenca", type="integer"),
     *                  @OA\Property(property="id_vendedor", type="string"),
     *                  @OA\Property(property="status", type="integer"),
     *                  @OA\Property(property="obs", type="string"),
     *                  @OA\Property(property="dt_cadastro", type="string"),
     *                  @OA\Property(property="dt_modificado", type="string"),
     *                  @OA\Property(property="vendedor", type="object",
     *                                @OA\Property(property="id", type="integer"),
     *                                @OA\Property(property="nome", type="string"),
     *                              ),
     *                     )
     *                   ),
     *                  
     *               ),
     *             
     *         ),  
     *         @OA\Response(
     *              response=401,
     *              description="Não autorizado"
     *          )
     * ),
     *          
     * )
     **/
    public function index()
    {
        try {
            $resultado = Dispositivo::where('fk_empresa', $this->usuario->fk_empresa)
                ->with("vendedor:id,nome")
                ->get();

            $dispositivos["cabecalho"] = [
                "qtdDispositivosCadastrados" => count($resultado),
                "qtdDispositivosAtivos" => Dispositivo::on("system")->where([
                    ['fk_empresa', $this->usuario->fk_empresa],
                    ['status', 1]
                ])->count(),
                "qtdDispositivosInativos" => Dispositivo::on("system")->where([
                    ['fk_empresa', $this->usuario->fk_empresa],
                    ['status', 0]
                ])->count(),
                "versaoApp" => VersaoApp::on("system")->orderBy("versao", "DESC")->pluck("versao")->take(1)[0],
                "qtd_contratado" => VwQtdLicencasEmpresa::find($this->usuario->fk_empresa)->qtd_contratado
            ];

            $dispositivos["dispositivos"] = $resultado;
            return $this->service->verificarErro($dispositivos);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    /**  
     * @OA\Post(
     *     path="/api/local/dispositivo",
     *     summary="Cria um novo cadastro de dispositivo",
     *     tags={"Dispositivo"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="marca", type="string"),
     *             @OA\Property(property="codigo", type="string"),
     *             @OA\Property(property="modelo", type="string"),
     *             @OA\Property(property="versaoApp", type="string"),
     *             @OA\Property(property="versao_android", type="string"),
     *             @OA\Property(property="imei", type="string"),
     *             @OA\Property(property="licenca", type="boolean"),
     *             @OA\Property(property="id_vendedor", type="string"),
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="obs", type="string"),
     *             @OA\Property(property="dt_cadastro", type="string"),
     *             @OA\Property(property="dt_modificado", type="string"),
     *             @OA\Property(property="utiliza_horario", type="boolean"),
     *            @OA\Property(property="horarios", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="seg_i", type="string"),
     *                             @OA\Property(property="seg_f", type="string"),
     *                             @OA\Property(property="ter_i", type="string"),
     *                             @OA\Property(property="ter_f", type="string"),
     *                             @OA\Property(property="qua_i", type="string"),
     *                             @OA\Property(property="qua_f", type="string"),
     *                             @OA\Property(property="qui_i", type="string"),
     *                             @OA\Property(property="qui_f", type="string"),
     *                             @OA\Property(property="sex_i", type="string"),
     *                             @OA\Property(property="sex_f", type="string"),
     *                             @OA\Property(property="sab_i", type="string"),
     *                             @OA\Property(property="sab_f", type="string"),
     *                             @OA\Property(property="dom_i", type="string"),
     *                             @OA\Property(property="dom_f", type="string"),
     *                             @OA\Property(property="status_seg", type="boolean"),
     *                             @OA\Property(property="status_ter", type="boolean"),
     *                             @OA\Property(property="status_qua", type="boolean"),
     *                             @OA\Property(property="status_qui", type="boolean"),
     *                             @OA\Property(property="status_sex", type="boolean"),
     *                             @OA\Property(property="status_sab", type="boolean"),
     *                             @OA\Property(property="status_dom", type="boolean"),
     *                         )
     *                     ),
     *                     @OA\Property(property="configuracao_dispositivos", type="array",
     *                         @OA\Items(
     *                   
     *                             @OA\Property(property="fk_tipo_configuracao", type="integer"),
     *                             @OA\Property(property="valor", type="string")
     *                         )
     *                     )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Registro salvo"),
     *     @OA\Response(response="400", description="A API irá informar onde se encontra o erro.")
     * )
     *
     **/
    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_DISPOSITIVO_CENTRAL);

            if ($request->status == self::DISPOSITIVO_ATIVO) {
                $this->service->lanceExcecaoSeNaoHouverLicencaDisponivel();
            }

            $dispositivo = new Dispositivo();
            $dispositivo->fk_empresa = $this->usuario->fk_empresa;
            $dispositivo->marca = $request->marca;
            $dispositivo->modelo = $request->modelo;
            $dispositivo->mac = $request->codigo;
            $dispositivo->password = bcrypt(env('DISPOSITIVO_PASSWORD'));
            $dispositivo->versaoApp = $request->versaoApp;
            $dispositivo->versao_android = $request->versao_android;
            $dispositivo->imei = $request->imei;
            $dispositivo->licenca = $request->licenca ?? self::LICENCA_AUTENTICADA;
            $dispositivo->id_vendedor = $request->id_vendedor;
            $dispositivo->obs = (isset($request->obs) && ($request->obs !== "")) ? $request->obs : null;
            $dispositivo->status = $request->status;
            $dispositivo->dt_cadastro = date("Y-m-d H:i");

            if ($dispositivo->save()) {

                if (isset($request->configuracao_dispositivos)) {

                    foreach ($request->configuracao_dispositivos as $configuracao) {

                        $this->service->verificarConfig($configuracao);
                        $configuracaoDispositivo = new ConfiguracaoDispositivo();
                        $configuracaoDispositivo->fk_empresa = $this->usuario->fk_empresa;
                        $configuracaoDispositivo->fk_dispositivo = $dispositivo->id;
                        $configuracaoDispositivo->fk_tipo_configuracao = $configuracao["tipo_configuracao"];
                        $configuracaoDispositivo->valor = $configuracao["valor"];
                        $configuracaoDispositivo->save();
                    }
                }

                if ($request->utiliza_horario == true) {

                    $horarioBase = new Horario;
                    $horarioBase->seg_i = $request->horarios["seg_i"];
                    $horarioBase->seg_f = $request->horarios["seg_f"];
                    $horarioBase->ter_i = $request->horarios["ter_i"];
                    $horarioBase->ter_f = $request->horarios["ter_f"];
                    $horarioBase->qua_i = $request->horarios["qua_i"];
                    $horarioBase->qua_f = $request->horarios["qua_f"];
                    $horarioBase->qui_i = $request->horarios["qui_i"];
                    $horarioBase->qui_f = $request->horarios["qui_f"];
                    $horarioBase->sex_i = $request->horarios["sex_i"];
                    $horarioBase->sex_f = $request->horarios["sex_f"];

                    $horarioBase->status_seg = $request->horarios["status_seg"];
                    $horarioBase->status_ter = $request->horarios["status_ter"];
                    $horarioBase->status_qua = $request->horarios["status_qua"];
                    $horarioBase->status_qui = $request->horarios["status_qui"];
                    $horarioBase->status_sex = $request->horarios["status_sex"];

                    if ($horarioBase->save()) {
                        $horarioDispositivo = new HorarioUtilizacaoDispositivo();
                        $horarioDispositivo->fk_empresa = $this->usuario->fk_empresa;
                        $horarioDispositivo->fk_horario = $horarioBase->id;
                        $horarioDispositivo->fk_dispositivo = $dispositivo->id;
                        $horarioDispositivo->id_vendedor = $request->id_vendedor;
                        $horarioDispositivo->status = $request->utiliza_horario;
                        $horarioDispositivo->save();
                    }
                }
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(
                ['error' => true, 'message' => $ex->getMessage()],
                $ex->getCode() > 400 ? $ex->getCode() : 400
            );
        }
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/dispositivo/{id}",
     *     summary="Lista os dispositivos do vendedor baseado no id do dispositivo passado",
     *     description="Lista os vendedores do dispositivo.",
     *     operationId="Lista os vendedores do dispositivo.",
     *     tags={"Dispositivo"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do dispositivo",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array com os dispositivos do vendedor e suas informações",
     *         @OA\JsonContent(type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="fk_empresa", type="integer"),
     *                     @OA\Property(property="marca", type="string"),
     *                     @OA\Property(property="mac", type="string"),
     *                     @OA\Property(property="modelo", type="string"),
     *                     @OA\Property(property="versaoApp", type="string"),
     *                     @OA\Property(property="versao_android", type="string"),
     *                     @OA\Property(property="imei", type="string"),
     *                     @OA\Property(property="licenca", type="integer"),
     *                     @OA\Property(property="id_vendedor", type="string"),
     *                     @OA\Property(property="status", type="integer"),
     *                     @OA\Property(property="obs", type="string"),
     *                     @OA\Property(property="dt_cadastro", type="string"),
     *                     @OA\Property(property="dt_modificado", type="string"),
     *                     @OA\Property(property="horarios", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="seg_i", type="string"),
     *                             @OA\Property(property="seg_f", type="string"),
     *                             @OA\Property(property="ter_i", type="string"),
     *                             @OA\Property(property="ter_f", type="string"),
     *                             @OA\Property(property="qua_i", type="string"),
     *                             @OA\Property(property="qua_f", type="string"),
     *                             @OA\Property(property="qui_i", type="string"),
     *                             @OA\Property(property="qui_f", type="string"),
     *                             @OA\Property(property="sex_i", type="string"),
     *                             @OA\Property(property="sex_f", type="string"),
     *                             @OA\Property(property="sab_i", type="string"),
     *                             @OA\Property(property="sab_f", type="string"),
     *                             @OA\Property(property="dom_i", type="string"),
     *                             @OA\Property(property="dom_f", type="string"),
     *                             @OA\Property(property="status_seg", type="boolean"),
     *                             @OA\Property(property="status_ter", type="boolean"),
     *                             @OA\Property(property="status_qua", type="boolean"),
     *                             @OA\Property(property="status_qui", type="boolean"),
     *                             @OA\Property(property="status_sex", type="boolean"),
     *                             @OA\Property(property="status_sab", type="boolean"),
     *                             @OA\Property(property="status_dom", type="boolean"),
     *                             @OA\Property(property="pivot", type="object",
     *                                 @OA\Property(property="fk_dispositivo", type="string"),
     *                                 @OA\Property(property="fk_horario", type="string"),
     *                                 @OA\Property(property="id", type="string"),
     *                                 @OA\Property(property="fk_empresa", type="string"),
     *                                 @OA\Property(property="id_vendedor", type="string"),
     *                                 @OA\Property(property="status", type="string"),
     *                             )
     *                         )
     *                     ),
     *                     @OA\Property(property="configuracao_dispositivos", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="fk_empresa", type="integer"),
     *                             @OA\Property(property="fk_dispositivo", type="integer"),
     *                             @OA\Property(property="fk_tipo_configuracao", type="integer"),
     *                             @OA\Property(property="valor", type="string")
     *                         )
     *                     )
     *                 )
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *     )
     * )
     **/
    public function show($id)
    {
        return  $this->service->verificarErro(Dispositivo::where([['fk_empresa', $this->usuario->fk_empresa], ['id', $id]])
            ->with(["horarios", "configuracao_dispositivos", "vendedor:id,nome"])
            ->get());
    }

    /**
     * @OA\Get(
     *     path="/api/tenant/dispositivo/vendedor/{id}",
     *     summary="Lista os dispositivos do vendedor baseado no id passado",
     *     description="Lista os dispositivos do vendedor.",
     *     operationId="Lista os dispositivos do vendedor.",
     *     tags={"Dispositivo"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do vendedor",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array com os dispositivos do vendedor e suas informações",
     *         @OA\JsonContent(type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="fk_empresa", type="integer"),
     *                     @OA\Property(property="marca", type="string"),
     *                     @OA\Property(property="mac", type="string"),
     *                     @OA\Property(property="modelo", type="string"),
     *                     @OA\Property(property="versaoApp", type="string"),
     *                     @OA\Property(property="versao_android", type="string"),
     *                     @OA\Property(property="imei", type="string"),
     *                     @OA\Property(property="licenca", type="integer"),
     *                     @OA\Property(property="id_vendedor", type="string"),
     *                     @OA\Property(property="status", type="integer"),
     *                     @OA\Property(property="obs", type="string"),
     *                     @OA\Property(property="dt_cadastro", type="string"),
     *                     @OA\Property(property="dt_modificado", type="string"),
     *                     @OA\Property(property="horarios", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="seg_i", type="string"),
     *                             @OA\Property(property="seg_f", type="string"),
     *                             @OA\Property(property="ter_i", type="string"),
     *                             @OA\Property(property="ter_f", type="string"),
     *                             @OA\Property(property="qua_i", type="string"),
     *                             @OA\Property(property="qua_f", type="string"),
     *                             @OA\Property(property="qui_i", type="string"),
     *                             @OA\Property(property="qui_f", type="string"),
     *                             @OA\Property(property="sex_i", type="string"),
     *                             @OA\Property(property="sex_f", type="string"),
     *                             @OA\Property(property="sab_i", type="string"),
     *                             @OA\Property(property="sab_f", type="string"),
     *                             @OA\Property(property="dom_i", type="string"),
     *                             @OA\Property(property="dom_f", type="string"),
     *                             @OA\Property(property="status_seg", type="boolean"),
     *                             @OA\Property(property="status_ter", type="boolean"),
     *                             @OA\Property(property="status_qua", type="boolean"),
     *                             @OA\Property(property="status_qui", type="boolean"),
     *                             @OA\Property(property="status_sex", type="boolean"),
     *                             @OA\Property(property="status_sab", type="boolean"),
     *                             @OA\Property(property="status_dom", type="boolean"),
     *                             @OA\Property(property="pivot", type="object",
     *                                 @OA\Property(property="fk_dispositivo", type="string"),
     *                                 @OA\Property(property="fk_horario", type="string"),
     *                                 @OA\Property(property="id", type="string"),
     *                                 @OA\Property(property="fk_empresa", type="string"),
     *                                 @OA\Property(property="id_vendedor", type="string"),
     *                                 @OA\Property(property="status", type="string"),
     *                             )
     *                         )
     *                     ),
     *                     @OA\Property(property="configuracao_dispositivos", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="fk_empresa", type="integer"),
     *                             @OA\Property(property="fk_dispositivo", type="integer"),
     *                             @OA\Property(property="fk_tipo_configuracao", type="integer"),
     *                             @OA\Property(property="valor", type="string")
     *                         )
     *                     )
     *                 )
     *             ),
     *       @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *      ),
     *     ),   
     * )
     **/
    public function showVendedor($id)
    {
        return $this->service->verificarErro(Dispositivo::where([['fk_empresa', $this->usuario->fk_empresa], ['id_vendedor', $id]])
            ->with(
                ['horarios' => function ($relation) use ($id) {
                    $relation->where([
                        ['fk_empresa', $this->usuario->fk_empresa],
                        ['id_vendedor', $id]
                    ]);
                }]
            )
            ->with(
                ['configuracao_dispositivos' => function ($relation) {
                    $relation->where('fk_empresa', $this->usuario->fk_empresa);
                }]
            )
            ->get());
    }
    /**
     * @OA\Put(
     *     path="/api/dispositivo/{id}",
     *     summary="Atualiza o dispositivo",
     *     description="Atualiza um dispositivo existente",
     *     tags={"Dispositivo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do dispositivo",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="marca", type="string"),
     *             @OA\Property(property="codigo", type="string"),
     *             @OA\Property(property="modelo", type="string"),
     *             @OA\Property(property="versaoApp", type="string"),
     *             @OA\Property(property="versao_android", type="string"),
     *             @OA\Property(property="imei", type="string"),
     *             @OA\Property(property="licenca", type="boolean"),
     *             @OA\Property(property="id_vendedor", type="string"),
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="obs", type="string"),
     *             @OA\Property(property="dt_cadastro", type="string"),
     *             @OA\Property(property="dt_modificado", type="string"),
     *             @OA\Property(property="utiliza_horario", type="boolean"),
     *             @OA\Property(property="horarios", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="seg_i", type="string"),
     *                             @OA\Property(property="seg_f", type="string"),
     *                             @OA\Property(property="ter_i", type="string"),
     *                             @OA\Property(property="ter_f", type="string"),
     *                             @OA\Property(property="qua_i", type="string"),
     *                             @OA\Property(property="qua_f", type="string"),
     *                             @OA\Property(property="qui_i", type="string"),
     *                             @OA\Property(property="qui_f", type="string"),
     *                             @OA\Property(property="sex_i", type="string"),
     *                             @OA\Property(property="sex_f", type="string"),
     *                             @OA\Property(property="sab_i", type="string"),
     *                             @OA\Property(property="sab_f", type="string"),
     *                             @OA\Property(property="dom_i", type="string"),
     *                             @OA\Property(property="dom_f", type="string"),
     *                             @OA\Property(property="status_seg", type="boolean"),
     *                             @OA\Property(property="status_ter", type="boolean"),
     *                             @OA\Property(property="status_qua", type="boolean"),
     *                             @OA\Property(property="status_qui", type="boolean"),
     *                             @OA\Property(property="status_sex", type="boolean"),
     *                             @OA\Property(property="status_sab", type="boolean"),
     *                             @OA\Property(property="status_dom", type="boolean"),
     *                         )
     *                     ),
     *                     @OA\Property(property="configuracao_dispositivos", type="array",
     *                         @OA\Items(
     *                   
     *                             @OA\Property(property="fk_tipo_configuracao", type="integer"),
     *                             @OA\Property(property="valor", type="string")
     *                         )
     *                     )
     *         )
     *     ),
     *     @OA\Response(response="200",description="Dipositivo atualizado com sucesso"),
     *     @OA\Response(response="400",description="A API irá informar onde se encontra o erro."),
     *     @OA\Response(response="404",description="Dispositivo não encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_DISPOSITIVO_CENTRAL, $id);

            $dispositivo = Dispositivo::find($id);

            if ($dispositivo->status == self::DISPOSITIVO_INATIVO && $request->status) {
                $this->service->lanceExcecaoSeNaoHouverLicencaDisponivel();
            }

            $dispositivo->marca = $request->marca;
            $dispositivo->modelo = $request->modelo;
            $dispositivo->mac = $request->codigo;
            $dispositivo->versaoApp = $request->versaoApp;
            $dispositivo->versao_android = $request->versao_android;
            $dispositivo->imei = $request->imei;
            $dispositivo->licenca = $request->licenca ?? $dispositivo->licenca;
            $dispositivo->id_vendedor = $request->id_vendedor;
            $dispositivo->obs = (isset($request->obs) && ($request->obs !== "")) ? $request->obs : null;
            $dispositivo->status = $request->status;
            $dispositivo->dt_modificado = date("Y-m-d H:i");

            if ($dispositivo->update()) {

                if (isset($request->configuracao_dispositivos)) {

                    foreach ($request->configuracao_dispositivos as $configuracao) {

                        $this->service->verificarConfig($configuracao);

                        $configuracaoDispositivo = ConfiguracaoDispositivo::where([
                            ['fk_empresa', $this->service->usuarioLogado()->fk_empresa],
                            ['fk_dispositivo', $id],
                            ['fk_tipo_configuracao', $configuracao["tipo_configuracao"]],
                        ])->first();

                        $configuracaoDispositivo->valor = $configuracao["valor"];
                        $configuracaoDispositivo->update();
                    }
                }

                if (isset($request->horarios)) {

                    $horarioBase = Horario::find($request->horarios["id"]);
                    $horarioBase->seg_i = $request->horarios["seg_i"];
                    $horarioBase->seg_f = $request->horarios["seg_f"];
                    $horarioBase->ter_i = $request->horarios["ter_i"];
                    $horarioBase->ter_f = $request->horarios["ter_f"];
                    $horarioBase->qua_i = $request->horarios["qua_i"];
                    $horarioBase->qua_f = $request->horarios["qua_f"];
                    $horarioBase->qui_i = $request->horarios["qui_i"];
                    $horarioBase->qui_f = $request->horarios["qui_f"];
                    $horarioBase->sex_i = $request->horarios["sex_i"];
                    $horarioBase->sex_f = $request->horarios["sex_f"];

                    $horarioBase->status_seg = $request->horarios["status_seg"];
                    $horarioBase->status_ter = $request->horarios["status_ter"];
                    $horarioBase->status_qua = $request->horarios["status_qua"];
                    $horarioBase->status_qui = $request->horarios["status_qui"];
                    $horarioBase->status_sex = $request->horarios["status_sex"];

                    if ($horarioBase->update()) {

                        $horarioDispositivo = HorarioUtilizacaoDispositivo::where(
                            [
                                ['fk_empresa', $this->service->usuarioLogado()->fk_empresa],
                                ['fk_horario', $horarioBase->id],
                                ['fk_dispositivo', $id],
                                ['id_vendedor', $request->id_vendedor],
                            ]
                        )->first();

                        $horarioDispositivo->status = $request->utiliza_horario;
                        $horarioDispositivo->update();
                    }
                }
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
    /** @OA\Delete(
     *     path="/api/tenant/dispositivo/{id}",
     *     summary="Apaga o dispositivo baseado no ID",
     *     description="Apaga o dispositivo baseado no ID",
     *     tags={"Dispositivo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do dispositivo",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Registro excluido com sucesso."
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description= "ID não informado."
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description= "Este ID não foi encontrado."
     *     )
     *     
     * )
     */
    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $dispositivo = Dispositivo::find($id);

            if (!isset($dispositivo)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($dispositivo->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
