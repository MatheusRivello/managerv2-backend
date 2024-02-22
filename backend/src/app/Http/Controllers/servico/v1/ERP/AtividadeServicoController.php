<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Atividade;
use App\Services\BaseService;
use Exception;

class AtividadeServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_ATIVIDADE;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Atividade' . CLASS_SERVICE;
        $this->entity = AtividadeServicoController::class;
        $this->firstOrNew = ["id_filial", "id_retaguarda"];
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
                "atividade" => $service->getAllIds("atividade"),
            ];

            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];
                    
                    $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                    $idAtividade = isset($vetor["atividade"][$item["id_filial"]][$item["id_retaguarda"]]) ? $vetor["atividade"][$item["id_filial"]][$item["id_retaguarda"]] : NULL;

                    if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";

                    if (!is_null($erroValidacaoIds)) {
                        $erros[] = "Nao existe - " . $erroValidacaoIds;
                        $erroValidacaoIds = NULL;
                    } else {
                        $item["status"] = 1; // atribuindo valor 1=ativo no campo status

                        if (!is_null($idAtividade)) {
                            $concluido = self::atualizarInserir($item);

                            if ($concluido > 0 && !isset($concluido["erro"])) {
                                $totalatualizados++;
                            } else {
                                $erros[] = ['erro' => ERRO_ATUALIZAR, 'id_retaguarda' => $item["id_retaguarda"], 'id_filial' => $item["id_filial"]];
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
        return parent::store($dados, Atividade::class, ["id_filial", "id_retaguarda"], self::_nomeCamposDb());
    }
    
    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Atividade::class, ["id_filial", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_retaguarda", "descricao", "status");
    }
}
