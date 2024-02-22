<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\VendaPlanoProduto;
use App\Services\BaseService;
use Exception;

class VendaPlanoProdutoServicoController extends BaseServicoController
{
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_VENDA_PLANO_PRODUTO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Vendaplanoproduto' . CLASS_SERVICE;
        $this->entity = VendaPlanoProdutoServicoController::class;
        $this->firstOrNew = ["id_filial", "id_cliente", "id_produto", "nfs_num"];
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
                "cliente" => $service->getAllIds("cliente"),
                "produto" => $service->getAllIds("produto")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["produto"])) $erroValidacaoIds .= "(produto)";
            if (is_null($vetor["cliente"])) $erroValidacaoIds .= "(Cliente)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao(self::_valoresNull($array[$i]), self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idProduto = isset($vetor["produto"][$item["id_filial"]][$item["id_produto"]]) ? $vetor["produto"][$item["id_filial"]][$item["id_produto"]] : NULL;
                        $idCliente = isset($vetor["cliente"][$item["id_filial"]][$item["id_cliente"]]) ? $vetor["cliente"][$item["id_filial"]][$item["id_cliente"]] : NULL;

                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idProduto)) $erroValidacaoIds .= "(Produto={$item["id_produto"]})";
                        if (is_null($idCliente)) $erroValidacaoIds .= "(Cliente={$item["id_cliente"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            $item["id_produto"] = intval($idProduto);
                            $item["id_cliente"] = intval($idCliente);
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
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, VendaPlanoProduto::class, ["id_filial", "id_cliente", "id_produto", "nfs_num"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, VendaPlanoProduto::class, ["id_filial", "id_cliente", "id_produto", "nfs_num"], self::_nomeCamposDb());
    }


    protected function _valoresNull($dados)
    {
        // if ($dados["nfs_emissao"] == "") $dados["nfs_emissao"] = NULL;
        // if ($dados["nfs_doc"] == "") $dados["nfs_doc"] = NULL;

        return $dados;
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial", "id_cliente", "id_produto",
            "nfs_num", "qtd_disponivel", "qtd_contratada",
            "valor_unitario", "unidade"
        );
    }
}
