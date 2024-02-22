<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\ProdutoSt;
use App\Services\BaseService;
use Exception;

class ProdutoStServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PRODUTO_ST;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Produtost' . CLASS_SERVICE;
        $this->entity = ProdutoStServicoController::class;
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
        return parent::store($dados, ProdutoSt::class, ["id_filial", "id_produto"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, ProdutoSt::class, ["id_filial", "id_produto"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial", "id_produto", "frete_icms", "frete_ipi",
            "tipo_contribuinte", "uf", "aliquota_icms", "aliquota_icms_st",
            "valor_referencia", "class_pauta_mva", "pauta",
            "mva", "reducao_icms", "reducao_icms_st", "modo_calculo",
            "calcula_ipi", "incide_ipi_base", "tipo_mva"
        );
    }

    protected function _valoresNull($dados)
    {
        if (!isset($dados["aliquota_icms"]) || $dados["aliquota_icms"] == "" || is_null($dados["aliquota_icms"])) $dados["aliquota_icms"] = 0.00;
        if (!isset($dados["aliquota_icms_st"]) || $dados["aliquota_icms_st"] == "" || is_null($dados["aliquota_icms_st"])) $dados["aliquota_icms_st"] = 0.00;
        if (!isset($dados["valor_referencia"]) || $dados["valor_referencia"] == "" || is_null($dados["valor_referencia"])) $dados["valor_referencia"] = 0.00;
        if (!isset($dados["class_pauta_mva"]) || $dados["class_pauta_mva"] == "" || is_null($dados["class_pauta_mva"])) $dados["class_pauta_mva"] = 0;
        if (!isset($dados["pauta"]) || $dados["pauta"] == "" || is_null($dados["pauta"])) $dados["pauta"] = 0.00;
        if (!isset($dados["mva"]) || $dados["mva"] == "" || is_null($dados["mva"])) $dados["mva"] = 0.00;
        if (!isset($dados["reducao_icms"]) || $dados["reducao_icms"] == "" || is_null($dados["reducao_icms"])) $dados["reducao_icms"] = 0.00;
        if (!isset($dados["reducao_icms_st"]) || $dados["reducao_icms_st"] == "" || is_null($dados["reducao_icms_st"])) $dados["reducao_icms_st"] = 0.00;
        if (!isset($dados["calcula_ipi"]) || $dados["calcula_ipi"] == "" || is_null($dados["calcula_ipi"])) $dados["calcula_ipi"] = 0;
        if (!isset($dados["frete_icms"]) || $dados["frete_icms"] == "" || is_null($dados["frete_icms"])) $dados["frete_icms"] = 0;
        if (!isset($dados["frete_ipi"]) || $dados["frete_ipi"] == "" || is_null($dados["frete_ipi"])) $dados["frete_ipi"] = 0;
        if (!isset($dados["tipo_mva"]) || $dados["tipo_mva"] == "" || is_null($dados["tipo_mva"])) $dados["tipo_mva"] = 0;

        return $dados;
    }
}
