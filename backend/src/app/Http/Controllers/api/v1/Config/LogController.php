<?php

namespace App\Http\Controllers\Api\v1\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Central\Log;
use App\Models\Central\LogApi;
use App\Models\Central\LogContato;
use App\Models\Central\LogDispositivo;
use App\Models\Central\LogMobile;
use App\Models\Central\SincronismoLog;
use App\Services\BaseService;
use Exception;

class LogController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }
    /**
     * @OA\Get(
     *    path="/api/local/log",
     *    summary="Lista os logs do painel",
     *    description="Lista os logs do painel.",
     *    operationId="lista os logs",
     *    tags={"Logs"},
     *    @OA\Response(
     *        response="200",
     *        description="Devolve um array de logs.",
     *        @OA\JsonContent(
     *            @OA\Property(property="current_page", type="integer"),
     *            @OA\Property(property="data", type="array", @OA\Items(
     *                @OA\Property(property="id", type="integer"),
     *                @OA\Property(property="tipo", type="integer"),
     *                @OA\Property(property="tipoAcesso", type="string"),
     *                @OA\Property(property="fKEmpresa", type="string"),
     *                @OA\Property(property="fKUsuario", type="string"),
     *                @OA\Property(property="idCliente", type="integer"),
     *                @OA\Property(property="ip", type="string"),
     *                @OA\Property(property="dtCadastro", type="string"),
     *                @OA\Property(property="tabela", type="string"),
     *                @OA\Property(property="mensagem", type="string"),
     *                @OA\Property(property="conteudo", type="string")
     *            ))
     *        )
     *    ),
     *    @OA\Response(
     *        response="401",
     *        description="Token expirado."
     *    )
     * )
     */
    public function index()
    {
        return  $this->service->verificarErro(
            Log::select(
                'id',
                'tipo',
                'tipo_acesso as tipoAcesso',
                'fk_empresa as fKEmpresa',
                'fk_usuario as fKUsuario',
                'id_cliente as idCliente',
                'ip',
                'dt_cadastro as dtCadastro',
                'tabela',
                'mensagem',
                'conteudo'
            )
                ->where('fk_empresa', $this->service->usuarioLogado()->fk_empresa)->paginate(20)
        );
    }
    /**
     * @OA\Get(
     *    path="/api/local/log/filtrar/{id}",
     *    summary="Lista o log filtrado",
     *    description="Lista o log com base no id passado na rota.",
     *    operationId="lista o log com base no filtro",
     *    tags={"Logs"},
     *    @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do log",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *    @OA\Response(
     *        response="200",
     *        description="Devolve o log solicitado.",
     *        @OA\JsonContent(type="array",@OA\Items(
     *                @OA\Property(property="id", type="integer"),
     *                @OA\Property(property="tipo", type="integer"),
     *                @OA\Property(property="tipo_acesso", type="string"),
     *                @OA\Property(property="fk_empresa", type="string"),
     *                @OA\Property(property="fk_usuario", type="string"),
     *                @OA\Property(property="id_cliente", type="integer"),
     *                @OA\Property(property="ip", type="string"),
     *                @OA\Property(property="dt_cadastro", type="string"),
     *                @OA\Property(property="tabela", type="string"),
     *                @OA\Property(property="mensagem", type="string"),
     *                @OA\Property(property="conteudo", type="string")))
     *        ),
     *    @OA\Response(
     *        response="401",
     *        description="Token expirado."
     *    )
     * )
     */
    public function showLog($id)
    {
        return  $this->service->verificarErro(Log::where('id', $id)->get());
    }

    /**
     * @OA\Post(
     *    path="/api/local/log",
     *    summary="Salva um registro de log",
     *    description="Salva o registro do log.",
     *    operationId="salva o log",
     *    tags={"Logs"},
     *    @OA\RequestBody(
     *       @OA\JsonContent(ref="App\Models\Tenant\Log")
     *    ),
     *    @OA\Response(response="200", description="Registro salvo"),
     *    @OA\Response(response="400", description="A API irá informar onde se encontra o erro."),
     *    @OA\Response(response="401", description="Token Expirado.")
     * )
     */
    public function storeLog(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_LOG);

            $log = new Log();
            $log->tipo =  $request->tipo;
            $log->tipo_acesso = $request->tipo_acesso;
            $log->fk_empresa = $this->service->usuarioLogado()->fk_empresa;;
            $log->fk_usuario = $request->fk_usuario;
            $log->id_cliente = $request->id_cliente;
            $log->ip = $request->ip();
            $log->tabela = $request->tabela;
            $log->mensagem = $request->mensagem;
            $log->conteudo = $request->conteudo;
            $log->save();

            return response()->json(['message' => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function indexContato()
    {
        return  $this->service->verificarErro(LogContato::where('fk_empresa', $this->service->usuarioLogado()->fk_empresa)->paginate(20));
    }

    public function showLogsContato($id)
    {
        return  $this->service->verificarErro(LogContato::where('id', $id)->get());
    }

    public function storeContato(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_LOG_CONTATO);

            $logContato = new LogContato();
            $logContato->fk_empresa =  $this->service->usuarioLogado()->fk_empresa;
            $logContato->id_cliente =  $request->id_cliente;
            $logContato->nome =  $request->nome;
            $logContato->email =  $request->email;
            $logContato->telefone =  $request->telefone;
            $logContato->mensagem =  $request->mensagem;
            $logContato->ip =   $request->ip();
            $logContato->dt_enviado =  $request->dt_enviado;
            $logContato->status =  $request->status;
            $logContato->save();

            return response()->json(['message' => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function indexMobile()
    {
        return  $this->service->verificarErro(LogMobile::paginate(20));
    }

    public function showLogsMobile($id)
    {
        return  $this->service->verificarErro(LogMobile::where('id', $id)->get());
    }

    public function storeMobile(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_LOG_MOBILE);

            $logMobile = new LogMobile();
            $logMobile->uri =  $request->uri;
            $logMobile->method =  $request->method;
            $logMobile->authorized = $request->authorized;
            $logMobile->params =  $request->params;
            $logMobile->api_key =  $request->api_key;
            $logMobile->ip_address =  $request->ip();
            $logMobile->response_code = $request->response_code;
            $logMobile->response = $request->response;
            $logMobile->time =  $request->time;
            $logMobile->rtime =  $request->rtime;
            $logMobile->save();

            return response()->json(['message' => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function indexSincronismo()
    {
        return  $this->service->verificarErro(SincronismoLog::where('id_empresa', $this->service->usuarioLogado()->fk_empresa)->get());
    }

    public function showLogsSincronismo($id)
    {
        return  $this->service->verificarErro(SincronismoLog::where('id_empresa', $id)->get());
    }

    public function storeLogsSincronismo(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_LOG_SINCRONISMO);

            $sincronismoLog = SincronismoLog::firstOrNew(["id_empresa" => $this->service->usuarioLogado()->fk_empresa]);
            $sincronismoLog->save();

            return response()->json(['message' => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function indexApi()
    {
        return  $this->service->verificarErro(LogApi::paginate(20));
    }

    public function showLogsApi($id)
    {
        return  $this->service->verificarErro(LogApi::where('id', $id)->get());
    }

    public function storeLogsApi(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_LOG_API);

            $logApi = new LogApi();
            $logApi->uri =  $request->uri;
            $logApi->method =  $request->method;
            $logApi->params =  $request->params;
            $logApi->api_key =  $request->api_key;
            $logApi->ip_address =  $request->ip();
            $logApi->time =  $request->time;
            $logApi->rtime =  $request->rtime;
            $logApi->authorized =  $request->authorized;
            $logApi->response_code =  $request->response_code;
            $logApi->response =  $request->response;
            $logApi->save();

            return response()->json(['message' => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function indexDispositivo()
    {
        return  $this->service->verificarErro(LogDispositivo::select(
            "id",
            "mac",
            "fk_empresa as fkEmpresa",
            "data",
            "descricao",
            "contexto",
            "codigoErro",
            "status",
            "versaoApp",
            "tipo",
            "ip",
            "dt_cadastro as dtCadastro",
            "resolvido",
            "dt_resolvido as dtResolvido"
        )
            ->where('fk_empresa', $this->service->usuarioLogado()->fk_empresa)->paginate(20));
    }

    public function showLogsDispositivo($id)
    {
        return  $this->service->verificarErro(LogDispositivo::where('id', $id)->get());
    }

    public function storeDispositivo(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_LOG_DISPOSITIVO);

            $logDispositivo = new LogDispositivo();
            $logDispositivo->mac =  $request->mac;
            $logDispositivo->fk_empresa =  $this->service->usuarioLogado()->fk_empresa;
            $logDispositivo->data = $request->data;
            $logDispositivo->descricao =  $request->descricao;
            $logDispositivo->contexto =  $request->contexto;
            $logDispositivo->codigoErro =  $request->codigoErro;
            $logDispositivo->status =  $request->status;
            $logDispositivo->versaoApp =  $request->versaoApp;
            $logDispositivo->tipo =  $request->tipo;
            $logDispositivo->ip =  $request->ip();
            $logDispositivo->dt_resolvido = $request->dt_resolvido;
            $logDispositivo->resolvido =  $request->resolvido;
            $logDispositivo->save();

            return response()->json(['message' => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
