<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\ProtabelaIten;
use App\Services\BaseService;
use Exception;

class ProdutotabelaitensServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PROTABELA_ITENS;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = self::_dadosClass()["metodo"];
        $this->entity = ProdutotabelaitensServicoController::class;
        $this->firstOrNew = ["id_filial", "id_produto", "id_protabela_preco"];
        $this->acaoTabela = self::_dadosClass()["acao"];
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

            if (!is_null($erroPacote))
                $erros = $erroPacote;

            $vetor = [
                "filial" => $service->getAllId("filial"),
                "produto" => $service->getAllIds("produto"),
                "proTabelaPreco" => $service->getAllIds("protabela_preco")
            ];

            if (is_null($vetor["filial"]))
                $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["produto"]))
                $erroValidacaoIds .= "(produto)";
            if (is_null($vetor["proTabelaPreco"]))
                $erroValidacaoIds .= "(Pro Tabela Preco)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao(self::_valoresNull($array[$i]), self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idProduto = isset($vetor["produto"][$item["id_filial"]][$item["id_produto"]]) ? $vetor["produto"][$item["id_filial"]][$item["id_produto"]] : NULL;
                        $idProTabelaPreco = isset($vetor["proTabelaPreco"][$item["id_filial"]][$item["id_protabela_preco"]]) ? $vetor["proTabelaPreco"][$item["id_filial"]][$item["id_protabela_preco"]] : NULL;

                        if (is_null($idFilial))
                            $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idProduto))
                            $erroValidacaoIds .= "(Produto={$item["id_produto"]})";
                        if (is_null($idProTabelaPreco))
                            $erroValidacaoIds .= "(Pro Tabela Preco={$item["id_protabela_preco"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            unset($item["id_filial"]);
                            $item["id_produto"] = $idProduto;
                            $item["id_protabela_preco"] = $idProTabelaPreco;

                            $vetorInsercao[] = $item;
                            $totalInseridos++;
                        }

                    } else {
                        $erros[] = $validacao;
                    }
                }

                if (isset($vetorInsercao)) {
                    $retornoInsert = self::insertBatch($vetorInsercao);

                    if (!is_null($retornoInsert))
                        $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
                }
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function insertBatch($dados)
    {
        return parent::inserirVetoresApagaTabela($dados, ProtabelaIten::class, self::_dadosClass()["metodo"], self::_dadosClass()["acao"]);
    }

    protected function _valoresNull($dados)
    {
        if ($dados["unitario"] == "" || is_null($dados["unitario"]))
            $dados["unitario"] = 0.00;
        if ($dados["qevendamax"] == "" || is_null($dados["qevendamax"]))
            $dados["qevendamax"] = 0.00;
        if ($dados["qevendamin"] == "" || is_null($dados["qevendamin"]))
            $dados["qevendamin"] = 0.00;
        if ($dados["desconto"] == "" || is_null($dados["desconto"]))
            $dados["desconto"] = 0.00;
        if ($dados["desconto2"] == "" || is_null($dados["desconto2"]))
            $dados["desconto2"] = 0.00;
        if ($dados["desconto3"] == "" || is_null($dados["desconto3"]))
            $dados["desconto3"] = 0.00;

        return $dados;
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial",
            "id_produto",
            "id_protabela_preco",
            "unitario",
            "status",
            "qevendamax",
            "qevendamin",
            "desconto",
            "desconto2",
            "desconto3"
        );
    }
    
    public function _dadosClass()
    {
        return array(
            "metodo" => 'Produtotabelaitens' . CLASS_SERVICE,
            "acao" => 1
        );
    }
}