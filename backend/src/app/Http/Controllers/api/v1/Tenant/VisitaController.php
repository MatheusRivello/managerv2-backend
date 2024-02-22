<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Central\JustificativaVendedor;
use App\Models\Tenant\ClienteVisitaPlanner;
use App\Models\Tenant\JustificativaVisita;
use App\Models\Tenant\NotaFiscal;
use App\Models\Tenant\Vendedor;
use App\Models\Tenant\Visita;
use App\Models\Tenant\Pedido;
use App\Models\Tenant\VisitaSetores;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitaController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }

    public function getCabecalhoAgenda(Request $request, $intervaloData = NULL)
    {
        try {
            $query1 =  Visita::select(
                DB::raw("COUNT(DISTINCT visita.id) AS qtdVisitasAgendadas"),
                DB::raw("COUNT(visita.id_pedido_dispositivo) AS qtdPedidos"),
                DB::raw("COUNT(DISTINCT cliente.id) AS qtdClientes")
            )
                ->join("cliente", "visita.id_cliente", "=", "cliente.id")
                ->where(function ($filtro) use ($request, $intervaloData) {
                    if (!is_null($request->filial)) $filtro->whereIn("visita.id_filial", $request->filial);
                    if (!is_null($request->idVendedor)) $filtro->where("visita.id_vendedor", $request->idVendedor);
                    if (!is_null($request->pedidoDispositivo)) $filtro->whereIn("visita.id_pedido_dispositivo", $request->pedidoDispositivo);
                    if (!is_null($request->status)) $filtro->whereIn("visita.status", $request->status);
                    if (!is_null($request->motivo)) {
                        $key = array_search("0", $request->motivo);
                        if (!is_bool($key)) $filtro->orWhere("visita.id_motivo", NULL);
                        $filtro->whereIn("visita.id_motivo", $request->motivo);
                    }
                    if (!is_null($request->dataFixo)) $filtro->like("visita.dt_marcada", $request->dataFixo, "after");
                    if (!is_null($intervaloData)) $filtro->whereBetween('visita.dt_marcada', $intervaloData);
                    if (!is_null($request->intervaloData)) $filtro->whereBetween('visita.dt_marcada', $request->intervaloData);
                })
                ->get();
            $resultado = $query1;

            $query2 =  Visita::select(
                DB::raw("COUNT(DISTINCT visita.id) AS qtdVisitados")
            )
                ->where(function ($filtro) use ($request, $intervaloData) {
                    if (!is_null($request->filial)) $filtro->whereIn("visita.id_filial", $request->filial);
                    if (!is_null($request->idVendedor)) $filtro->where("visita.id_vendedor", $request->idVendedor);
                    if (!is_null($request->motivo)) $filtro->whereIn("visita.id_motivo", $request->motivo);
                    if (!is_null($request->dataFixo)) $filtro->like("visita.dt_marcada", $request->dataFixo, "after");
                    if (!is_null($request->intervaloData)) $filtro->whereBetween('visita.dt_marcada', $request->intervaloData);
                    if (!is_null($intervaloData)) $filtro->whereBetween('visita.dt_marcada', $intervaloData);

                    $filtro->whereIn("visita.status", [STATUS_SEM_VENDA_VISITA, STATUS_COM_PEDIDO_VISITA]);
                })
                ->get();

            $resultado[0]["qtdVisitados"] = $query2[0]["qtdVisitados"];

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function getDadosAgenda(Request $request)
    {
        try {
            $resultado =  Visita::select(
                "visita.id",
                "visita.status",
                "visita.dt_marcada AS dtMarcada",
                "visita.hora_marcada AS horaMarcada",
                "visita.observacao",
                "visita.ordem",
                "visita.longitude",
                "visita.latitude",
                "visita.precisao",
                "visita.provedor",
                "visita.endereco_extenso_google AS enderecoExtensoGoogle",
                DB::raw("DATE_FORMAT(visita.dt_cadastro,'%d/%m/%Y %H:%i') AS dtCadastro"),
                "filial.emp_raz AS razaoSocialEmpresa",
                "filial.emp_fan AS nomeFantasiaEmpresa",
                "motivo.id AS idMotivo",
                "motivo.descricao AS descricaoMotivo",
                "vendedor.nome AS nomeVendedor",
                "vendedor.id AS idVendedor",
                "cliente.id AS idCliente",
                "cliente.id_retaguarda AS idClienteRetaguarda",
                "cliente.razao_social AS razaoSocialCliente",
                "cliente.nome_fantasia AS nomeFantasiaCliente",
                "endereco.latitude AS latitudeCliente",
                "endereco.longitude AS longitudeCliente",
                "endereco.logradouro AS enderecoCliente",
                "endereco.bairro AS bairroCliente",
                "cidade.descricao AS cidadeCliente",
                "endereco.numero AS numeroCliente",
                "endereco.uf AS ufCliente",
                "endereco.cep AS cepCliente"
            )
                ->join("cliente", "visita.id_cliente", "=", "cliente.id")
                ->join("filial", "filial.id", "=", "visita.id_filial")
                ->join("vendedor", "vendedor.id", "=", "visita.id_vendedor")
                ->join("motivo", "motivo.id", "=", "visita.id_motivo", "left")
                ->join("endereco", "cliente.id", "=", "endereco.id_cliente", "left")
                ->join("cidade", "cidade.id", "=", "endereco.id_cidade", "left")
                ->where(function ($filtro) use ($request) {
                    if (!is_null($request->filial)) $filtro->whereIn("visita.id_filial", $request->filial);
                    if (!is_null($request->vendedor)) $filtro->whereIn("visita.id_vendedor", $request->vendedor);
                    if (!is_null($request->pedidoDispositivo)) $filtro->whereIn("visita.id_pedido_dispositivo", $request->pedidoDispositivo);
                    if (!is_null($request->status)) $filtro->whereIn("visita.status", $request->status);
                    if (!is_null($request->motivo)) {
                        $key = array_search("0", $request->motivo);
                        if (!is_bool($key)) $filtro->orWhere("visita.id_motivo", NULL);
                        $filtro->whereIn("visita.id_motivo", $request->motivo);
                    }
                    if (!is_null($request->dataFixo)) $filtro->like("visita.dt_marcada", $request->dataFixo, "after");
                    if (!is_null($request->dataInicio) && !is_null($request->dataFim)) $filtro->where("visita.dt_marcada BETWEEN '{$request->dataInicio}' AND '{$request->dataFim}'");
                })
                ->get()
                ->groupBy("visita.id");

            return $this->service->verificarErro(isset($resultado[""]) ? $resultado[""] : $resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function listaVendedores(Request $request)
    {
        try {
            $vendedores = Vendedor::select(
                "id",
                DB::raw("(CASE tipo WHEN 0 THEN '0-VENDEDOR' 
                WHEN 1 THEN '1-SUPERVISOR' 
                WHEN 2 THEN '2-GERENTE' 
                ELSE 'DESCONHECIDO' END) as cargo "),
                "gerente",
                "supervisor",
                "nome",
                "saldoVerba",
                "status",
            )
                ->with('gerente:id,nome', 'supervisor:id,nome')
                ->where(function ($query) use ($request) {
                    if (!is_null($request->vendedor)) $query->whereIn('id', $request->vendedor);
                    if (!is_null($request->status)) $query->whereIn('status', $request->status);
                    if (!is_null($request->tipo)) $query->whereIn('tipo', $request->tipo);
                    if (!is_null($request->supervisor)) $query->whereIn('supervisor', $request->supervisor);
                    if (!is_null($request->gerente)) $query->whereIn('gerente', $request->gerente);
                })->get();

            $resultado = collect($vendedores)->each(function ($vendedor) {
                $vendedor->numeroPedidos = Pedido::where([
                    'dt_cadastro' => date('Y-m-d'),
                    'id_vendedor' => $vendedor->id
                ])->count();

                $agendadas = Visita::where([
                    'dt_marcada' => date('Y-m-d'),
                    'id_vendedor' => $vendedor->id
                ])->get();

                $efetuadas = $agendadas->whereIn('status', [
                    STATUS_VISITA_SEM_VENDA,
                    STATUS_VISITA_COM_VENDA,
                    STATUS_VISITA_FINALIZADA,
                    STATUS_VISITA_FINALIZADA_AFV
                ])->count();

                $semVisitas = $agendadas->whereIn('status', [STATUS_VISITA_SEM_VISITA])->count();

                $visitasAbertas = $agendadas->whereIn('status', [STATUS_VISITA_ABERTO])->count();

                $porcentagemVisitas = isset($agendadas) && $agendadas->count() > 0 ? $efetuadas * 100 / $agendadas->count() : 0;

                $vendedor->roteiro = [
                    "qtdAgendadas" => $agendadas->count(),
                    "qtdEfetuadas" => $efetuadas,
                    "qtdSemVisitas" => $semVisitas,
                    "qtdVisitasAbertas" => $visitasAbertas,
                    "porcentagem" => $porcentagemVisitas
                ];

                $ausencia = JustificativaVisita::select(
                    "id",
                    DB::raw("DATE_FORMAT(data_cadastro,'%d-%m-%Y') AS dataCadastro"),
                    "hora_ini",
                    "hora_fim",
                )
                    ->where([
                        'data_cadastro' => date('Y-m-d'),
                        'id_vendedor' => $vendedor->id
                    ])
                    ->with('motivo_ausencia:id,descricao')
                    ->where('hora_fim', ">=", date('H:i'))
                    ->first();

                $vendedor->ausencia = isset($ausencia) ? $ausencia : null;

                $vendedor->emVisita = Visita::whereBetween(
                    'hora_marcada',
                    [
                        date("H:i", strtotime("-30minutes")),
                        date("H:i", strtotime("+30minutes"))
                    ]
                )
                    ->where("dt_marcada", date("Y-m-d"))
                    ->count() > 0 ? true : false;

                return $vendedor;
            });

            $elementos = 20;
            $totalElementos = count($vendedores);
            $arrayResultado = [];
            $arrayResultado["total"] = $totalElementos;
            $arrayResultado["per_page"] = $elementos;
            $arrayResultado["data"] = $resultado;

            return $this->service->verificarErro($arrayResultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function indexJustificativaVisita()
    {
        try {
            $registros = JustificativaVisita::select(
                "justificativa_visita.id",
                "justificativa_visita.id_justificativa",
                "justificativa_visita.id_vendedor",
                "justificativa_visita.data_cadastro",
                "justificativa_visita.hora_ini",
                "justificativa_visita.hora_fim",
                "justificativa_vendedor.descricao"
            )
                ->join('central_afv.justificativa_vendedor', 'justificativa_visita.id_justificativa', '=', 'justificativa_vendedor.id')
                ->paginate(20);

            return $registros;
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function deleteJustificativaVisita($id)
    {
        try {
            if (!isset($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $registro = JustificativaVisita::find($id);

            if (!isset($registro)) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 403);
            }
            if ($registro->getRelacionamentosCount() > 0) {
                throw new Exception(EXISTE_RELACIONAMENTOS, 406);
            }

            if ($registro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function storeJustificativaVisita(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_JUSTIFICATIVA_VISITA);

            $registro = JustificativaVisita::firstOrNew(['id' => $request->id]);
            $registro->id_justificativa = $request->motivo;
            $registro->id_vendedor = $request->idVendedor;
            $registro->hora_ini = $request->Inicio;
            $registro->hora_fim = $request->Fim;
            $registro->data_cadastro = $request->dataCadastro;
            $registro->hora_ini = $request->inicio;
            $registro->hora_fim = $request->fim;
            //$registro->dia_todo = $request->diaTodo;
            $registro->save();

            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function indexJustificativaVendedor()
    {
        try {
            $registros = JustificativaVendedor::paginate(20);
            return $registros;
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function deleteJustificativaVendedor($id)
    {
        try {
            if (!isset($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $registro = JustificativaVendedor::find($id);

            if (!isset($registro)) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 403);
            }
            if ($registro->getRelacionamentosCount() > 0) {
                throw new Exception(EXISTE_RELACIONAMENTOS, 406);
            }

            if ($registro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function storeJustificativaVendedor(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_JUSTIFICATIVA_VENDEDOR);

            $registro = JustificativaVendedor::firstOrNew(['id' => $request->id]);
            $registro->id_empresa = $request->idEmpresa;
            $registro->descricao = $request->descricao;
            $registro->save();

            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function indexVisitaSetores()
    {
        try {
            $registros = VisitaSetores::paginate(20);
            return $registros;
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }
    public function deleteVisitaSetores($id)
    {
        try {
            if (!isset($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $registro = VisitaSetores::find($id);

            if (!isset($registro)) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 403);
            }
            if ($registro->getRelacionamentosCount() > 0) {
                throw new Exception(EXISTE_RELACIONAMENTOS, 406);
            }

            if ($registro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }


    public function storeVisitaSetores(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_VISITA_SETORES);

            $registro = VisitaSetores::firstOrNew(['id' => $request->id]);
            $registro->id_filial = $request->idFilial;
            $registro->descricao = $request->descricao;
            $registro->cor = $request->cor;
            $registro->status = $request->status;
            $registro->save();

            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function indexVisitaPlanner()
    {
        try {
            $registros = ClienteVisitaPlanner::paginate(20);
            return $registros;
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function deleteClienteVisitaPlanner($id)
    {
        try {
            if (!isset($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $registro = ClienteVisitaPlanner::find($id);

            if (!isset($registro)) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 403);
            }
            if ($registro->getRelacionamentosCount() > 0) {
                throw new Exception(EXISTE_REGISTRO, 406);
            }
            if ($registro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function storeClienteVisitaPlanner(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_VISITA_PLANNER);

            $registro = ClienteVisitaPlanner::firstOrNew(['id_cliente' => $request->idCliente, 'id_vendedor' => $request->idVendedor]);
            $registro->id_cliente = $request->idCliente;
            $registro->id_vendedor = $request->idVendedor;
            $registro->prioridade = $request->prioridade;
            $registro->ordem = $request->ordem;
            $registro->dias = $request->dias;
            $registro->id_setor = $request->idSetor;

            $registro->save();

            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    public function getVisitaFilter(Request $request)
    {
        $vendedor = Visita::where(function ($query) use ($request) {
            if (!is_null($request->id)) $query->whereIn('id', $request->id);
            if (!is_null($request->id_vendedor)) $query->whereIn('id_vendedor', $request->idVendedor);
            if (!is_null($request->ordem)) $query->whereIn('ordem', $request->ordem);
        })->get();

        $vendedor->numeroPedidos = Pedido::where([
            'id_vendedor' => $vendedor->id_vendedor
        ])->count();

        $agendadas = Visita::where(['id_vendedor' => $vendedor->id_vendedor])->get();

        $efetuadas = $agendadas->whereIn('status', [
            STATUS_VISITA_SEM_VENDA,
            STATUS_VISITA_COM_VENDA,
            STATUS_VISITA_FINALIZADA,
            STATUS_VISITA_FINALIZADA_AFV
        ])->count();

        $semVisitas = $agendadas->whereIn('status', [STATUS_VISITA_SEM_VISITA])->count();

        $visitasAbertas = $agendadas->whereIn('status', [STATUS_VISITA_ABERTO])->count();

        $vendedor->roteiro = [
            "qtdAgendadas" => $agendadas->count(),
            "qtdEfetuadas" => $efetuadas,
            "qtdSemVisitas" => $semVisitas,
            "qtdVisitasAbertas" => $visitasAbertas,
        ];

        return  $vendedor;
    }


    public function getVisitaSimples(Request $request)
    {
        try {
            $registros = [];

            $dataInicial = isset($request->dataInicial) ? $request->dataInicial : date('Y-m-d');
            $dataFinal = isset($request->dataFinal) ? $request->dataFinal : $dataInicial;
            $intervaloData = [$dataInicial, $dataFinal];

            $registros['data']['head'] = $this->getCabecalhoAgenda($request, $intervaloData);


            $registros['data']['visitas'] = Visita::select(
                "visita.id",
                DB::raw("visita.id_vendedor as idVendedor"),
                DB::raw("visita.endereco_extenso_google as endereco"),
                "visita.latitude",
                "visita.longitude",
                DB::raw("visita.dt_marcada as dataMarcada"),
                DB::raw("visita.hora_marcada as horaMarcada"),
                DB::raw("visita.hora_inicio as horaInicial"),
                DB::raw("visita.hora_final as horaFinal"),
                "cliente.razao_social",
                "cliente.nome_fantasia",
                DB::raw("cliente.id_retaguarda as idCliente"),
                "visita.observacao",
                "visita.ordem",
                "visita.status",
                DB::raw("CONCAT(visita.dt_marcada, ' ', visita.hora_inicio) as visitaIni"),
                DB::raw("CONCAT(visita.dt_marcada, ' ', visita.hora_final) as visitaFim"),
                DB::raw("CONCAT(REPLACE(DATE_FORMAT(visita.dt_marcada, '%d%m%y'), '-', ''), visita.ordem) as codOrdem"),
            )
                ->where(function ($query) use ($request) {
                    if (!is_null($request->filial)) $query->whereIn("visita.id_filial", $request->filial);
                    if (!is_null($request->idVendedor)) $query->where("visita.id_vendedor", $request->idVendedor);
                    if (!is_null($request->pedidoDispositivo)) $query->whereIn("visita.id_pedido_dispositivo", $request->pedidoDispositivo);
                    if (!is_null($request->status)) $query->whereIn("visita.status", $request->status);
                    if (!is_null($request->motivo)) {
                        $key = array_search("0", $request->motivo);
                        if (!is_bool($key)) $query->orWhere("visita.id_motivo", NULL);
                        $query->whereIn("visita.id_motivo", $request->motivo);
                    }
                    if (!is_null($request->dataFixo)) $query->like("visita.dt_marcada", $request->dataFixo, "after");
                })
                ->where('visita.id_vendedor', $request->idVendedor)
                ->whereBetween('dt_marcada', [$dataInicial, $dataFinal])
                ->join("cliente", "visita.id_cliente", "=", "cliente.id")
                ->get();


            return response()->json($registros, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tenant/visita/detalhada",
     *     summary="Lista as visitas de forma detalhada.",
     *     description="Lista todas as visitas de forma detalhada.",
     *     operationId="lista as visitas de forma detalhada",
     *     tags={"Visitas"},
     *     @OA\Parameter(
     *         name="filial",
     *         in="query",
     *         description="ID da filial que detém as visitas.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID da filial")
     *     ),
     *     @OA\Parameter(
     *         name="vendedor",
     *         in="query",
     *         description="ID dos vendedores.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID do vendedor")
     *     ),
     *     @OA\Parameter(
     *         name="motivo",
     *         in="query",
     *         description="IDs dos motivos nos quais deverão ser filtradas as visitas",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="Se for igual a 1 trará somente o supervisor")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devolve um array de visitas detalhadas",
     *         @OA\JsonContent(type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="status", type="integer"),
     *                 @OA\Property(property="dtMarcada", type="string"),
     *                 @OA\Property(property="horaMarcada", type="string"),
     *                 @OA\Property(property="observacao", type="string"),
     *                 @OA\Property(property="ordem", type="integer"),
     *                 @OA\Property(property="longitude", type="string"),
     *                 @OA\Property(property="latitude", type="string"),
     *                 @OA\Property(property="precisao", type="string"),
     *                 @OA\Property(property="provedor", type="string"),
     *                 @OA\Property(property="enderecoExtensoGoogle", type="string"),
     *                 @OA\Property(property="dtCadastro", type="datetime"),
     *                 @OA\Property(property="razaoSocialEmpresa", type="string"),
     *                 @OA\Property(property="nomeFantasiaEmpresa", type="string"),
     *                 @OA\Property(property="idMotivo", type="integer"),
     *                 @OA\Property(property="descricaoMotivo", type="string"),
     *                 @OA\Property(property="nomeVendedor", type="string"),
     *                 @OA\Property(property="idVendedor", type="integer"),
     *                 @OA\Property(property="idCliente", type="integer"),
     *                 @OA\Property(property="idClienteRetaguarda", type="integer"),
     *                 @OA\Property(property="razaoSocialCliente", type="string"),
     *                 @OA\Property(property="nomeFantasiaDoCliente", type="string"),
     *                 @OA\Property(property="latitudeCliente", type="string"),
     *                 @OA\Property(property="longitudeCliente", type="string"),
     *                 @OA\Property(property="enderecoCliente", type="string"),
     *                 @OA\Property(property="bairroCliente", type="string"),
     *                 @OA\Property(property="cidadeCliente", type="string"),
     *                 @OA\Property(property="numeroCliente", type="string"),
     *                 @OA\Property(property="ufCliente", type="string"),
     *                 @OA\Property(property="cepCliente", type="string")
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function getPedidoDetalhado($idVisita)
    {
        try {
            $registro = [];

            $visita = Visita::whereId($idVisita)

                ->select(
                    "id",
                    "id_motivo",
                    "id_cliente",
                    "id_vendedor",
                    "id_pedido_dispositivo",
                    DB::raw('CASE
                WHEN (status = 0) then "Aberto"
                WHEN (status = 1) then "Sem Visita"
                WHEN(status = 2)then "Sem venda"
                WHEN(status = 3) then "Com venda"
                WHEN(status = 5) then "Visita Finalizada (Agendada ERP)"
                WHEN(status = 7 and agendado_erp = 1 ) then "Visita Finalizada Fora de rota (Agendada ERP fora de 100 metros)"
                WHEN (status = 7 and agendado_erp = 0 ) then "Visita finalizada fora de roteiro(agendada ERP)"
                else
                "Error Status"
                end  as statusVisita'),
                    "sinc_erp",
                    "dt_marcada",
                    "hora_marcada",
                    "observacao",
                    "ordem",
                    "latitude",
                    "longitude",
                    "lat_inicio",
                    "lng_inicio",
                    "lat_final",
                    "lng_final",
                    "hora_inicio",
                    "hora_final",
                    "dt_cadastro",
                    "agendado_erp",
                    "endereco_extenso_google",
                    "id_filial"
                )
                ->with(['filial:id,emp_raz', 'cliente:id,id_retaguarda,razao_social,nome_fantasia'])
                ->first();

            if (isset($visita)) {
                $notaFiscais = NotaFiscal::select(
                    "nota_fiscal.id",
                    "nota_fiscal.ped_num",
                    "nota_fiscal.nfs_doc",
                    DB::raw("IF(nota_fiscal.nfs_custo IS null, 0, nota_fiscal.nfs_custo) as nfs_custo"),
                    DB::raw("IF(nota_fiscal.nfs_custo IS null , 0, IF (nota_fiscal.nfs_custo > 0, (100 - ((nota_fiscal.nfs_custo / nota_fiscal.ped_total) *100)), 0)) AS margem_ped"),
                    DB::raw("IF(nota_fiscal.nfs_custo IS null , 0, IF (nota_fiscal.nfs_custo > 0, ((nota_fiscal.ped_total / nota_fiscal.nfs_custo) * 100) -100, 0)) AS markup_ped"),
                    "nota_fiscal.ped_total",
                    DB::raw("IF(nota_fiscal.nfs_custo IS null , 0, IF (nota_fiscal.nfs_custo > 0, (100 - ((nota_fiscal.nfs_custo / nota_fiscal.nfs_valbrut) *100)), 0)) AS margem_nfs"),
                    DB::raw("IF(nota_fiscal.nfs_custo IS null , 0, IF (nota_fiscal.nfs_custo > 0, ((nota_fiscal.nfs_valbrut / nota_fiscal.nfs_custo) * 100) -100, 0)) AS markup_nfs"),
                    "nota_fiscal.nfs_valbrut",
                    DB::raw("DATE_FORMAT(nota_fiscal.ped_emissao,'%d-%m-%Y') AS ped_emissao"),
                    DB::raw("DATE_FORMAT(nota_fiscal.nfs_emissao,'%d-%m-%Y') AS nfs_emissao"),
                    DB::raw('CASE
     WHEN (nota_fiscal.nfs_status = 1) then "Totalmente atendido"
     WHEN(nota_fiscal.nfs_status = 2)then "Parcialmente atendido"
     WHEN(nota_fiscal.nfs_status = 3) then "Não atendido"
     WHEN(nota_fiscal.nfs_status = 4) then "Devolução"
     WHEN(nota_fiscal.nfs_status = 5) then "Bonificação"
     WHEN (nota_fiscal.nfs_status = 6 ) then "Aguardando faturamento"
     WHEN (nota_fiscal.nfs_status = 7 ) then "Cancelado"
     else
     "Error Status"
     end  as statusNota'),
                    "nota_fiscal.id_filial",
                    "nota_fiscal.id_vendedor",

                )
                    ->with(['vendedor:id,nome', 'filial:id,emp_raz,emp_fan'])
                    ->join("pedido", "nota_fiscal.ped_num", "=", "pedido.id_retaguarda")
                    ->where('pedido.id_cliente', $visita->id_cliente)
                    ->get();

                $head = Visita::where('id_cliente', $visita->id_cliente);

                $registro['detalhamento']['head'] = [
                    "totVisitasRealizadas" => $head->whereIn('status', [
                        STATUS_VISITA_ABERTO,
                        STATUS_VISITA_SEM_VISITA,
                        STATUS_VISITA_SEM_VENDA,
                        STATUS_VISITA_COM_VENDA,
                        STATUS_VISITA_FINALIZADA,
                        STATUS_VISITA_FINALIZADA_AFV
                    ])->count(),
                    "totVisitasSemVendas" => $head->whereIn('status', [
                        STATUS_VISITA_ABERTO,
                        STATUS_VISITA_SEM_VISITA,
                        STATUS_VISITA_SEM_VENDA
                    ])->count(),
                    "totVisitasComVendas" => $head->whereIn('status', [
                        STATUS_VISITA_COM_VENDA
                    ])->count(),
                    "filial" => $visita->filial->id . " - " .  $visita->filial->emp_raz,
                    "cliente" => $visita->cliente->id . " - " .  $visita->cliente->razao_social,
                    "status" => $visita->statusVisita
                ];

                $registro['detalhamento']['visita'] = $visita;

                $registro['detalhamento']['pedidos'] = $notaFiscais;
            }
            return response()->json($this->service->verificarErro($registro), 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    /** 
     * @OA\Post(
     *     path="/api/tenant/visita/cadastro",
     *     summary="Cadastro de visita.",
     *     description="Cadastra uma nova visita.",
     *     operationId="Cadastra uma nova visita na empresa.",
     *     tags={"Visitas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            @OA\Property(property="idFilial", type="integer"),
     *            @OA\Property(property="idMotivo", type="integer"),
     *            @OA\Property(property="idVendedor", type="integer"),
     *            @OA\Property(property="idCliente", type="integer"),
     *            @OA\Property(property="idPedidoDispositivo", type="integer"),
     *            @OA\Property(property="status", type="integer"),
     *            @OA\Property(property="sincErp", type="integer"),
     *            @OA\Property(property="dtMarcada", type="string",format="date"),
     *            @OA\Property(property="horaMarcada", type="string"),
     *            @OA\Property(property="observacao", type="string"),
     *            @OA\Property(property="ordem", type="integer"),
     *            @OA\Property(property="latitude", type="string"),
     *            @OA\Property(property="longitude", type="string"),
     *            @OA\Property(property="precisao", type="string"),
     *            @OA\Property(property="provedor", type="string"),
     *            @OA\Property(property="latInicio", type="string"),
     *            @OA\Property(property="lngInicio", type="string"),
     *            @OA\Property(property="latFinal", type="string"),
     *            @OA\Property(property="lngFinal", type="string"),
     *            @OA\Property(property="precisaoInicio", type="string"),
     *            @OA\Property(property="precisaoFinal", type="string"),
     *            @OA\Property(property="horaIni", type="string"),
     *            @OA\Property(property="dtCadastro", type="string", format= "date"),
     *            @OA\Property(property="enderecoExtensoGoogle", type="string"),
     *            @OA\Property(property="agendadoErp", type="integer"),
     *          )
     *     ),
     *     @OA\Response(response="200", description="Registro salvo"),
     *     @OA\Response(response="400", description="A API irá informar onde se encontra o erro.")
     *    )
     * 
     */
    public function cadastrarVisita(Request $request)
    {
        try {

            $this->service->verificarCamposRequest($request, RULE_VISITA);

            $registro = new Visita();
            $registro->id_filial = $request->idFilial;
            $registro->id_motivo = $request->idMotivo;
            $registro->id_vendedor = $request->idVendedor;
            $registro->id_cliente = $request->idCliente;
            $registro->id_pedido_dispositivo = $request->idPedidoDispositivo;
            $registro->status = $request->status;
            $registro->sinc_erp = $request->sincErp;
            $registro->dt_marcada = $request->dtMarcada;
            $registro->hora_marcada = $request->horaMarcada;
            $registro->observacao = $request->observacao;
            $registro->ordem = $request->ordem;
            $registro->latitude = $request->latitude;
            $registro->longitude = $request->longitude;
            $registro->precisao = $request->precisao;
            $registro->provedor = $request->provedor;
            $registro->lat_inicio = $request->latInicio;
            $registro->lng_inicio = $request->lngInicio;
            $registro->lat_final = $request->latFinal;
            $registro->lng_final = $request->lngFinal;
            $registro->precisao_inicio = $request->precisaoInicio;
            $registro->precisao_final = $request->precisaoFinal;
            $registro->hora_inicio = $request->horaInicio;
            $registro->hora_final = $request->horaFinal;
            $registro->dt_cadastro = $request->dtCadastro;
            $registro->endereco_extenso_google = $request->enderecoExtensoGoogle;
            $registro->agendado_erp = $request->agendadoErp;
            $registro->save();

            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
