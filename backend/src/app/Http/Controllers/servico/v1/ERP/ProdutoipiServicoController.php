<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\ProdutoIpi;
use App\Services\BaseService;
use Exception;

class ProdutoipiServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PRODUTO_IPI;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Produtoipi' . CLASS_SERVICE;
        $this->entity = ProdutoipiServicoController::class;
        $this->firstOrNew = ["id_filial", "id_produto"];
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
                "produto" => $service->getAllIds("produto")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["produto"])) $erroValidacaoIds .= "(Produto)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao(self::_valoresNull($array[$i]), self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];


                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idProduto = isset($vetor["produto"][$item["id_filial"]][$item["id_produto"]]) ? $vetor["produto"][$item["id_filial"]][$item["id_produto"]] : NULL;

                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idProduto)) $erroValidacaoIds .= "(Produto={$item["id_produto"]})";


                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            $item["id_produto"] = $idProduto;
                            unset($item["id_filial"]);

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
        return parent::store($dados, ProdutoIpi::class, ["id_filial", "id_produto"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, ProdutoIpi::class, ["id_filial", "id_produto"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_produto", "tipi_mva", "tipi_mva_simples", "tipi_mva_fe_nac", "tipi_mva_fe_imp",
        "tipi_tpcalc", "tipi_aliquota", "tipi_pauta", "calcula_ipi");
    }

    protected function _valoresNull($dados)
    {
        if ($dados["tipi_mva"] == "" || is_null($dados["tipi_mva"])) $dados["tipi_mva"] = 0.00;
        if ($dados["tipi_mva_simples"] == "" || is_null($dados["tipi_mva_simples"])) $dados["tipi_mva_simples"] = 0.00;
        if ($dados["tipi_mva_fe_nac"] == "" || is_null($dados["tipi_mva_fe_nac"])) $dados["tipi_mva_fe_nac"] = 0.00;
        if ($dados["tipi_mva_fe_imp"] == "" || is_null($dados["tipi_mva_fe_imp"])) $dados["tipi_mva_fe_imp"] = 0.00;
        if ($dados["tipi_aliquota"] == "" || is_null($dados["tipi_aliquota"])) $dados["tipi_aliquota"] = 0.00;
        if ($dados["tipi_pauta"] == "" || is_null($dados["tipi_pauta"])) $dados["tipi_pauta"] = 0.00;

        return $dados;
    }
}
