<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\ProdutoDesctoQtd;
use App\Services\BaseService;
use Exception;

class ProdutodesctoqtdServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PRODUTO_DESCTO_QTD;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Produtodesctoqtd' . CLASS_SERVICE;
        $this->entity = ProdutodesctoqtdServicoController::class;
        $this->firstOrNew = ["id_filial", "id_produto", "id_protabela_preco"];
        $this->acaoTabela = 1;
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
                "produto" => $service->getAllIds("produto"),
                "proTabelaPreco" => $service->getAllIds("protabela_preco")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["produto"])) $erroValidacaoIds = "(Produto)";
            if (is_null($vetor["proTabelaPreco"])) $erroValidacaoIds .= "(Pro Tabela Preco)";

            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao(self::_valoresNull($array[$i]), self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];

                    $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                    $idTabela = isset($vetor["proTabelaPreco"][$item["id_filial"]][$item["id_protabela_preco"]]) ? $vetor["proTabelaPreco"][$item["id_filial"]][$item["id_protabela_preco"]] : NULL;
                    $idProduto = isset($vetor["produto"][$item["id_filial"]][$item["id_produto"]]) ? $vetor["produto"][$item["id_filial"]][$item["id_produto"]] : NULL;

                    if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                    if (is_null($idProduto)) $erroValidacaoIds = "(Produto={$item["id_produto"]})";
                    if (is_null($idTabela)) $erroValidacaoIds .= "(Tabela Preco={$item["id_protabela_preco"]})";

                    if (!is_null($erroValidacaoIds)) {
                        $erros[] = "Nao existe - " . $erroValidacaoIds;
                        $erroValidacaoIds = NULL;
                    } else {
                        unset($item["id_filial"]);
                        $item["id_produto"] = $idProduto;
                        $item["id_protabela_preco"] = $idTabela;

                        $vetorInsercao[] = $item;
                        $totalInseridos++;
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
        return parent::store($dados, ProdutoDesctoQtd::class, ["id_filial", "id_produto", "id_protabela_preco"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, ProdutoDesctoQtd::class, ["id_filial", "id_produto", "id_protabela_preco"], self::_nomeCamposDb());
    }

    protected function _valoresNull($dados)
    {
        if ((isset($dados["produtos"])) && ($dados["desconto"] == "" || is_null($dados["desconto"]))) {
            $dados["desconto"] = 0.00;
        }

        return $dados;
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_produto", "id_protabela_preco", "quantidade", "desconto");
    }
}
