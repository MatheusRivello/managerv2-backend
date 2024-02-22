<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\StatusPedido;
use App\Services\BaseService;
use Exception;

class StatusPedidoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_STATUS_PEDIDO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Statuspedido' . CLASS_SERVICE;
        $this->entity = StatusPedidoServicoController::class;
        $this->firstOrNew = ["id_filial", "id_pedido"];
        $this->acaoTabela = 0;
    }

    public static function atualizarDados($array, $erroPacote)
    {
        try {
            $erros = NULL;
            $vetorInsercao = NULL;
            $totalInseridos = 0;
            $totalatualizados = 0;
            $totalRegistros = count($array);
            $erroValidacaoIds = NULL;
            $service = new BaseService();

            if (!is_null($erroPacote)) $erros = $erroPacote;

            $vetor = [
                "filial" => $service->getAllId("filial")
            ];

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            $concluido = self::atualizarInserir($item);
                            if ($concluido > 0) {
                                $totalatualizados++;
                            } else {
                                $erros[] = ['erro' => ERRO_ATUALIZAR, 'id_pedido' => $item["id_pedido"], 'id_filial' => $item["id_filial"]];
                            }
                        }
                    } else {
                        $erros[] = $validacao;
                    }
                }

                if (!is_null($vetorInsercao)) {
                    $retornoInsert = self::atualizarInserirVetores($vetorInsercao);

                    if ($retornoInsert == NULL || $retornoInsert == 0) $erros[] = ["erro" => PACOTE_NAO_INSERIDO, "data" => $vetorInsercao];
                }
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, StatusPedido::class, ["id_filial", "id_pedido"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, StatusPedido::class, ["id_filial", "id_pedido"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_pedido", "data", "status");
    }
}