<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\ProtabelaPreco;
use App\Services\BaseService;
use Exception;

class ProdutoTabelaPrecoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PROTABELA_PRECO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Produtotabelapreco' . CLASS_SERVICE;
        $this->entity = ProdutoTabelaPrecoServicoController::class;
        $this->firstOrNew = ["id_filial", "id_retaguarda"];
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
                "filial" => $service->getAllId("filial"),
                "proTabelaPreco" => $service->getAllIds("protabela_preco")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao(self::_valoresNull($array[$i]), self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idProTabelaPreco = isset($vetor["proTabelaPreco"][$item["id_filial"]][$item["id_retaguarda"]]) ? $vetor["proTabelaPreco"][$item["id_filial"]][$item["id_retaguarda"]] : NULL;

                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            if (!is_null($idProTabelaPreco)) {
                                $concluido = self::atualizarInserir($item);

                                if ($concluido > 0) {
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
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, ProtabelaPreco::class, ["id_filial", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, ProtabelaPreco::class, ["id_filial", "id_retaguarda"], self::_nomeCamposDb());
    }

    protected function _valoresNull($dados)
    {
        if ($dados["tab_ini"] == "") $dados["tab_ini"] = NULL;
        if ($dados["tab_fim"] == "") $dados["tab_fim"] = NULL;

        return $dados;
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial", "id_retaguarda", "tab_desc",
            "tab_ini", "tab_fim", "gerar_verba"
        );
    }
}
