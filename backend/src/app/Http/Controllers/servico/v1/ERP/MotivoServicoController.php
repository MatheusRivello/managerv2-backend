<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Motivo;
use App\Services\BaseService;
use Exception;

class MotivoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_MOTIVO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Motivo' . CLASS_SERVICE;
        $this->entity = MotivoServicoController::class;
        $this->firstOrNew = ["id_filial", "descricao"];
        $this->acaoTabela = 2;
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
                "filial" => $service->getAllId("filial"),
                "motivo" => $service->getAllIds("motivo")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";


            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];

                    $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                    $idMotivo = isset($vetor["motivo"][$item["id_filial"]][$item["id"]]) ? $vetor["motivo"][$item["id_filial"]][$item["id"]] : NULL;

                    if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";

                    if (!is_null($erroValidacaoIds)) {
                        $erros[] = "Nao existe - " . $erroValidacaoIds;
                        $erroValidacaoIds = NULL;
                    } else {
                        $item["status"] = 1; // atribuindo valor 1=ativo no campo status

                        $idMotivoRetaguarda = $item["id"];
                        $item["id_retaguarda"] = $idMotivoRetaguarda;
                        unset($item["id"]);

                        if (!is_null($idMotivo)) {
                            $concluido = self::atualizarInserir($item);

                            if ($concluido > 0) {
                                $totalatualizados++;
                            } else {
                                $erros[] = ["erro" => ERRO_ATUALIZAR, "id" => $idMotivoRetaguarda, "id_filial" => $item["id_filial"]];
                            }
                        } else {
                            $vetorInsercao[] = $item;
                            $totalInseridos++;
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

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, Motivo::class, ["id_filial", "descricao"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Motivo::class, ["id_filial", "descricao"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id", "descricao", "id_filial", "tipo");
    }
}
