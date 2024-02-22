<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Tenant\Visita;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitaMobileController extends BaseMobileController
{
    protected $className;

    public function __construct()
    {
        $this->className = "Visita";
        $this->model = Visita::class;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    public function retornavisita(Request $request)
    {
        try {
            $data = $this->model::select(
                "id",
                "id_filial",
                "id_motivo",
                "id_vendedor",
                "id_cliente",
                "dt_marcada",
                DB::raw("DATE_FORMAT(hora_marcada, '%H:%i') AS hora_marcada"),
                "status",
                "observacao",
                "ordem",
                "sinc_erp",
                "latitude",
                "longitude",
                "provedor",
                "precisao",
                "lat_inicio",
                "lng_inicio",
                "lat_final",
                "lng_final",
                "precisao_inicio",
                "precisao_final",
                DB::raw("DATE_FORMAT(hora_inicio, '%H:%i') AS hora_inicio"),
                DB::raw("DATE_FORMAT(hora_final, '%H:%i') AS hora_final")
            )
                ->where("id_vendedor", $this->usuarioLogado()->id_vendedor)
                ->where("status", 0)
                ->where("dt_marcada", ">=", DB::raw("CURRENT_DATE()"))
                ->where("dt_marcada", "<=", DB::raw("DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)"))
                ->get();

            if (count($data) < 1) {
                $resposta = [
                    'status' => 'erro',
                    'code' => HTTP_NOT_FOUND,
                    'mensagem' => 'Nenhum registro localizado com o MAC ' . $this->mac
                ];
            } else {
                $resposta = [
                    'status' => 'sucesso',
                    'code' => HTTP_ACCEPTED,
                    'data' => $data
                ];
            }
            return response()->json($resposta, $resposta["code"]);
        } catch (Exception $ex) {
            return response()->json(['erro' => true, 'message' => $ex->getMessage()]);
        }
    }

    public function visitajson(Request $request)
    {
        try {
            $array = $request->json;
            $qtd_inserido = 0;
            $erros = [];
            $retorno = [];

            //VERIFICA SE O JSON É INVÁLIDO
            if (is_null($array)) {
                $resposta = $this->retorna_msg_erro(ERRO_JSON_INVALIDO, "", $array, "json");
                goto mensagem;
            }

            $id_vendedor = $this->usuarioLogado()->id_vendedor;

            foreach ($array as $item) {
                $item["id_vendedor"] = $id_vendedor;
                $item["id_pedido_dispositivo"] = $item["id_pedido"];
                $item["dt_marcada"] = date("Y-m-d", strtotime($item["dt_marcada"]));
                $item["hora_marcada"] = date("H:i:s", strtotime($item["hora_marcada"]));
                $item["hora_inicio"] = date("H:i:s", strtotime($item["hora_inicio"]));
                $item["hora_final"] = date("H:i:s", strtotime($item["hora_final"]));

                $validacao = $this->service->verificarCamposRequest($item, RULE_VISITA_SERVICO, null, null, 'return');

                $idAFV = $item["id_mobile"];

                if (!isset($validacao[0])) {
                    if (isset($item["id"])) {
                        $idMobile = $item["id"];
                        unset($item["id"]);
                    } else {
                        $idMobile = NULL;
                    }

                    unset($item["id_mobile"]);

                    $item["sinc_erp"] = PENDENTE_SYNC_ERP; // Quando cadastra (1), é para o sistema local entender que ele tera qua pegar essas informações para inserir ou atualizar

                    $item["endereco_extenso_google"] = $this->service->_getEnderecoGeolocalizacao($item["latitude"], $item["longitude"]);
                    $item["observacao"] = str_replace(array("\r\n", "\r", "\n", "\\r", "\\n", "\\r\\n", "\""), "", $item["observacao"]);
                    if (intval($item["ordem"]) == 0) {
                        $id_visita_interno = $this->inserirVisita($item, $this->mac, NULL);
                    } else {
                        $id_visita_interno = $this->atualizarVisita($item, $idMobile);
                        $item["id"] = $idMobile;
                        if (!$id_visita_interno) $erros[] = ["error" => "registro nao atualizado, ID={$idMobile}", "recebido" => $item];
                    }

                    if ($id_visita_interno === NULL) {
                        $erros[] = ["error" => "Falha ao inserir a visita", "recebido" => $item];
                    } else {
                        $retorno[] = [
                            "id_mobile" => $idMobile,
                            "id_mobile_afv" => $idAFV,
                            "id_nuvem" => $id_visita_interno,
                        ];
                        $qtd_inserido++;
                    }
                } else {
                    $erros[] = $validacao;
                }
            }

            if ((count($erros) > 0) && ($qtd_inserido > 0)) {
                $resposta = [
                    'code' => HTTP_OK,
                    'status' => 'sucesso_parcial',
                    'mensagem' => 'Alguns dados não foram inseridos/atualizados',
                    'data' => $erros
                ];
            } elseif ((count($erros) > 0) && ($qtd_inserido === 0)) {
                $resposta = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                    'mensagem' => 'Nenhum dado foi cadastrado',
                    'data' => $erros
                ];
            } else {
                $resposta = [
                    'code' => HTTP_OK,
                    'status' => 'sucesso',
                    'data' => $retorno
                ];
            }

            mensagem:
            return response()->json($resposta, $resposta["code"]);
        } catch (Exception $ex) {
            return response()->json(['erro' => true, 'message' => $ex->getMessage()]);
        }
    }

    private function inserirVisita($arrayItem)
    {
        $item = json_decode(json_encode($arrayItem));
        $visita = new $this->model;
        $visita->id_filial = $item->id_filial;
        $visita->id_motivo = $item->id_motivo;
        $visita->id_vendedor = $item->id_vendedor;
        $visita->id_cliente = $item->id_cliente;
        $visita->id_pedido_dispositivo = $item->id_pedido_dispositivo;
        $visita->dt_marcada = $item->dt_marcada;
        $visita->hora_marcada = $item->hora_marcada;
        $visita->status = $item->status;
        $visita->observacao = $item->observacao;
        $visita->ordem = $item->ordem;
        $visita->sinc_erp = $item->sinc_erp;
        $visita->latitude = $item->latitude;
        $visita->longitude = $item->longitude;
        $visita->provedor = $item->provedor;
        $visita->precisao = $item->precisao;
        $visita->lat_inicio = $item->lat_inicio;
        $visita->lng_inicio = $item->lng_inicio;
        $visita->lat_final = $item->lat_final;
        $visita->lng_final = $item->lng_final;
        $visita->precisao_inicio = $item->precisao_inicio;
        $visita->precisao_final = $item->precisao_final;
        $visita->hora_inicio = $item->hora_inicio;
        $visita->hora_final = $item->hora_final;
        $visita->endereco_extenso_google = $item->endereco_extenso_google;

        if ($visita->save()) {
            $resultado = $visita->id;
        } else {
            $resultado = false;
        }

        return $resultado;
    }

    private function atualizarVisita($arrayItem, $idNuvem)
    {
        $item = json_decode(json_encode($arrayItem));
        $visita = $this->model::find($idNuvem);

        if (isset($visita)) {
            $visita->id_filial = $item->id_filial;
            $visita->id_motivo = $item->id_motivo;
            $visita->id_vendedor = $item->id_vendedor;
            $visita->id_cliente = $item->id_cliente;
            $visita->id_pedido_dispositivo = $item->id_pedido_dispositivo;
            $visita->dt_marcada = $item->dt_marcada;
            $visita->hora_marcada = $item->hora_marcada;
            $visita->status = $item->status;
            $visita->observacao = $item->observacao;
            $visita->ordem = $item->ordem;
            $visita->sinc_erp = $item->sinc_erp;
            $visita->latitude = $item->latitude;
            $visita->longitude = $item->longitude;
            $visita->provedor = $item->provedor;
            $visita->precisao = $item->precisao;
            $visita->lat_inicio = $item->lat_inicio;
            $visita->lng_inicio = $item->lng_inicio;
            $visita->lat_final = $item->lat_final;
            $visita->lng_final = $item->lng_final;
            $visita->precisao_inicio = $item->precisao_inicio;
            $visita->precisao_final = $item->precisao_final;
            $visita->hora_inicio = $item->hora_inicio;
            $visita->hora_final = $item->hora_final;
            $visita->endereco_extenso_google = $item->endereco_extenso_google;

            if ($visita->save()) {
                $resultado = $visita->id;
            } else {
                $resultado = false;
            }
        } else {
            $resultado = false;
        }

        return $resultado;
    }
}
