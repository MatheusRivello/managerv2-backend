<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\ProdutoEstoque;
use App\Services\BaseService;
use Exception;

class ProdutoestoqueServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PRODUTO_ESTOQUE;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = self::_dadosClass()["metodo"];
        $this->entity = ProdutoestoqueServicoController::class;
        $this->firstOrNew = ["id_produto", "unidade"];
        $this->acaoTabela = self::_dadosClass()["acao"];
    }

    public function atualizarDados($array, $erroPacote)
    {
        try {
            $erros = NULL;
            $vetorInsercao = NULL;
            $totalInseridos = 0;
            $totalAtualizados = 0;
            $totalRegistros = count($array);
            $erroValidacaoIds = NULL;
            $service = new BaseService();
            if (!is_null($erroPacote))
                $erros = $erroPacote;

            $vetor = [
                "filial" => $service->getAllId("filial"),
                "produto" => $service->getAllIds("produto"),
                "estoque" => $service->getAllIdsOFF("produto_estoque")
            ];

            if (is_null($vetor["filial"]))
                $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["produto"]))
                $erroValidacaoIds = "(Produto)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                $dtModificado = date("Y-m-d H:i:s");

                foreach ($array as $key => $item) {

                    $validacao = $service->_validacao($item, self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idProduto = isset($vetor["produto"][$item["id_filial"]][$item["id_produto"]]) ? $vetor["produto"][$item["id_filial"]][$item["id_produto"]] : NULL;

                        if (is_null($idFilial))
                            $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idProduto))
                            $erroValidacaoIds .= "(Produto={$item["id_produto"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {

                            $item["id_produto"] = $idProduto;
                            unset($item["id_filial"]);

                            if (isset($vetor["estoque"][$item["id_produto"] . $item["unidade"]])) {
                                $item["id"] = $vetor["estoque"][$item["id_produto"] . $item["unidade"]];
                                $item["dt_modificado"] = $dtModificado;
                                $item["updated_at"] = $dtModificado;
                                $item["quantidade"] = floatval($item["quantidade"]);
                                unset($item["unidade"]);
                                unset($item["id_produto"]);

                                $vetorAtualizacao[] = $item;
                                $totalAtualizados++;
                            } else {
                                $vetorInsercao[] = $item;
                                $totalInseridos++;
                            }
                        }
                    } else {
                        $erros[] = $validacao;
                    }
                }

                unset($vetor);

                if (isset($vetorInsercao)) {
                    $retornoInsert = self::insertBatch($vetorInsercao);
                    if ($retornoInsert != NULL)
                        $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
                }

                if (isset($vetorAtualizacao)) {
                    $retornoAtualizacao = self::atualizarBatch($vetorAtualizacao);

                    if ($retornoAtualizacao != NULL)
                        $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
                }
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalAtualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function insertBatch($dados)
    {
        return parent::inserirVetores($dados, ProdutoEstoque::class);
    }

    private function atualizarBatch($dados)
    {
        return parent::updateVetores($dados, ProdutoEstoque::class, "id", ["quantidade"], LIMIT_INSERSAO_BATCH_1000, true);
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_produto", "quantidade", "unidade");
    }

    public function _dadosClass()
    {
        return array(
            "metodo" => 'Produtoestoque' . CLASS_SERVICE,
            "acao" => 1
        );
    }
}