<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Visita;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitaServicoController extends BaseServicoController
{
    public $service;

    public function __construct(BaseService $service)
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_VISITA;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Visita' . CLASS_SERVICE;
        $this->entity = VisitaServicoController::class;
        $this->firstOrNew = ["id"];
        $this->acaoTabela = 1;
        $this->customCasts = ["id_pedido", "numero_item"];
        $this->idEmpresa = $service->usuarioLogado()->fk_empresa;
        $this->model = Visita::class;
    }

    /**
     * Retorna a lista dos novos visitas
     */
    public function getNovos()
    {
        $data = $this->model::withCasts($this->util->convertValueJSON($this->model, NULL, true))->select(
            "visita.id",
            DB::raw("motivo.id_retaguarda AS id_motivo"),
            DB::raw("cliente.id_retaguarda AS id_cliente"),
            "cliente.id_filial",
            "visita.id_vendedor",
            "visita.dt_marcada",
            "visita.hora_marcada",
            "visita.status",
            "visita.sinc_erp",
            "visita.ordem",
            "visita.latitude",
            "visita.longitude",
            "visita.precisao",
            "visita.provedor",
            DB::raw("IF(visita.lat_inicio IS NULL, visita.latitude,visita.lat_inicio) AS lat_inicio"),
            DB::raw("IF(visita.lng_inicio IS NULL, visita.longitude, visita.lng_inicio) AS lng_inicio"),
            DB::raw("IF(visita.lat_final IS NULL, visita.longitude,visita.lat_final) AS lat_final"),
            DB::raw("IF(visita.lng_final IS NULL, visita.longitude,visita.lng_final) AS lng_final"),
            "visita.precisao_inicio",
            "visita.precisao_final",
            DB::raw("IF(visita.hora_inicio IS NULL,visita.hora_marcada,visita.hora_inicio) AS hora_inicio"),
            DB::raw("IF(visita.hora_final IS NULL,visita.hora_marcada,visita.hora_final) AS hora_final"),
            DB::raw("TRIM(TRIM(TRAILING '\r' FROM TRIM(TRAILING '\n' FROM TRIM(SUBSTRING(visita.observacao,1,30))))) AS observacao")
        )
            ->where("visita.sinc_erp", VISITA_NAO_SINC_ERP) // SO RETORNA OS REGISTROS NOVOS QUE PRECISAM ENVIAR PARA O BANCO DO SISTEMA LOCAL
            ->join("cliente", "visita.id_cliente", "=", "cliente.id")
            ->join("motivo", function ($query) {
                $query->on("motivo.id_retaguarda", "=", "visita.id_motivo");
                $query->on("motivo.id_filial", "=", "visita.id_filial");
            })
            ->limit(15)
            ->get();

        $resposta = count($data) > 0 ? [
            "status" => "sucesso",
            "code" => HTTP_ACCEPTED,
            "data" => $data
        ] : [
            "status" => "erro",
            "code" => HTTP_NOT_FOUND,
            "mensagem" => ERRO_REGISTRO_NAO_LOCALIZADO
        ];

        return response()->json($resposta, HTTP_CREATED);
    }

    /**
     * Atualiza O ID do retaguarda para os novos
     */
    public function atualizaVisitas(Request $request)
    {
        $log = [];
        $resposta = NULL;
        $resposta = $this->service->_validarArray($request->json);

        try {
            if (is_array($resposta) != TRUE) {
               
                foreach ($request->json as $valor => $chave) {
                    $id_interno = $chave; // ID DO BANCO DA NUVEM

                    if (!is_null($id_interno) && $id_interno != "") {

                        $dado = [
                            "id" => $id_interno,
                            "sinc_erp" => VISITA_SINC_ERP,
                        ];

                        $resultado = $this->atualizarInserir($dado);
                        
                        $msg = "Id Nuvem: {$id_interno} - ";

                        if ($resultado) {
                            $msg .= "OK";
                        } else {
                            $msg .= "FALHA";
                        }

                        $log[] = $msg;
                    }

                    $resposta = (count($log) > 0) ? [
                        "code" => HTTP_ACCEPTED,
                        "status" => "successo",
                        "mensagem" => $log
                    ] : [
                        "code" => HTTP_NOT_FOUND,
                        "status" => "error",
                        "mensagem" => "Verifique se foi enviado o Id corretamente"
                    ];
                }
            }

            return response()->json($resposta, $resposta["code"]);
        } catch (Exception $ex) {
            return  $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, Visita::class, ["id"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Visita::class, ["id"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id", "sinc_erp");
    }
}
