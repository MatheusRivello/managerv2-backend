<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Produto;
use App\Services\BaseService;
use Exception;

class ProdutoServicoController extends BaseServicoController
{
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PRODUTO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Produto' . CLASS_SERVICE;
        $this->entity = ProdutoServicoController::class;
        $this->firstOrNew = ["id_filial", "id_retaguarda"];
        $this->acaoTabela = 0;
    }

    public static function atualizarDados($array, $erroPacote)
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
                "statusProduto" => $service->getAllId("status_produto", TRUE),
                "produto" => $service->getAllIds("produto"),
                "grupo" => $service->getAllIds("grupo"),
                "subGrupo" => $service->getAllIds("subgrupo", "id_retaguarda", "id", "id_grupo"),
                "fornecedor" => $service->getAllIds("fornecedor"),
            ];

            if (is_null($vetor["filial"]))
                $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["statusProduto"]))
                $erroValidacaoIds .= "(Status Produto)";
            if (is_null($vetor["grupo"]))
                $erroValidacaoIds .= "(Grupo)";
            if (is_null($vetor["subGrupo"]))
                $erroValidacaoIds .= "(SubGrupo)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                foreach ($array as $key => $produto) {
                    $validacao = $service->_validacao(self::_valoresNull($produto), self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFornecedor = NULL;

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idProduto = isset($vetor["produto"][$item["id_filial"]][$item["id_retaguarda"]]) ? $vetor["produto"][$item["id_filial"]][$item["id_retaguarda"]] : NULL;
                        $idGrupo = isset($vetor["grupo"][$item["id_filial"]][$item["id_grupo"]]) ? $vetor["grupo"][$item["id_filial"]][$item["id_grupo"]] : NULL;
                        $idSubGrupo = isset($vetor["subGrupo"][$item["id_filial"]][$item["id_grupo"]][$item["id_subgrupo"]]) ? $vetor["subGrupo"][$item["id_filial"]][$item["id_grupo"]][$item["id_subgrupo"]] : NULL;
                        $idStatusProduto = isset($vetor["statusProduto"][$item["status"]]) ? $vetor["statusProduto"][$item["status"]] : NULL;

                        //Se caso for enviado o fornecedor e estiver o ID ele valida
                        if (isset($item["id_fornecedor"]) && $item["id_fornecedor"] !== "" && $item["id_fornecedor"] != 0 && !is_null($item["id_fornecedor"])) {
                            $idFornecedor = isset($vetor["fornecedor"][$item["id_filial"]][$item["id_fornecedor"]]) ? $vetor["fornecedor"][$item["id_filial"]][$item["id_fornecedor"]] : NULL;
                            if (is_null($idFornecedor))
                                $erroValidacaoIds .= "(Fornecedor={$item["id_fornecedor"]})";
                        } else {
                            $item["id_fornecedor"] = NULL;
                        }

                        if (is_null($idFilial))
                            $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idStatusProduto))
                            $erroValidacaoIds .= "(Status Produto={$item["status"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {

                            $item["id_grupo_new"] = $idGrupo;
                            $item["id_subgrupo_new"] = $idSubGrupo;

                            //Se nao encontrar nenhum status do produto ele salva o status como 99999
                            if (is_null($idStatusProduto)) {
                                $item["status"] = 99999;
                            } else {
                                $item["status"] = $idStatusProduto;
                            }

                            $item["id_fornecedor"] = $idFornecedor;
                            if (!is_null($idProduto)) {
                                $item["id"] = $idProduto;
                                $vetorAtualizacao[] = $item;
                                $totalAtualizados++;
                                // if ($concluido > 0) {
                                //     //unset($vetor["produto"][$item["id_filial"]][$item["id_retaguarda"]]); //Vai limpando o vetor
                                //     $totalatualizados++;
                                // } else {
                                //     $erros[] = ["erro" => ERRO_ATUALIZAR, "id_retaguarda" => $item["id_retaguarda"], "id_filial" => $item["id_filial"]];
                                // }
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
                    // dd($retornoAtualizacao);
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
        return parent::inserirVetores($dados, Produto::class);
    }

    private function atualizarBatch($dados)
    {
        return parent::updateVetores($dados, Produto::class, "id", self::_nomeCamposDb());
    }


    public function atualizarInserir($dados)
    {
        return parent::store($dados, Produto::class, ["id_filial", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Produto::class, ["id_filial", "id_retaguarda"], self::_nomeCamposDb());
    }

    protected function _valoresNull($dados)
    {
        if (!isset($dados["pro_inicio"]) || $dados["pro_inicio"] == "")
            $dados["pro_inicio"] = NULL;
        if (!isset($dados["pw_filial"]) || $dados["pw_filial"] == "")
            $dados["pw_filial"] = 1;
        if (!isset($dados["meta_title"]) || $dados["meta_title"] == "")
            $dados["meta_title"] = NULL;
        if (!isset($dados["meta_description"]) || $dados["meta_description"] == "")
            $dados["meta_description"] = NULL;
        if (!isset($dados["meta_keywords"]) || $dados["meta_keywords"] == "")
            $dados["meta_keywords"] = NULL;
        if (!isset($dados["frete_gratis"]) || $dados["frete_gratis"] == "")
            $dados["frete_gratis"] = NULL;
        if (!isset($dados["descricao_curta"]) || $dados["descricao_curta"] == "")
            $dados["descricao_curta"] = NULL;
        if (!isset($dados["ncm"]) || $dados["ncm"] == "")
            $dados["ncm"] = NULL;
        if (!isset($dados["dun"]) || $dados["dun"] == "")
            $dados["dun"] = NULL;
        if (!isset($dados["pw_filial"]) || $dados["pw_filial"] == "")
            $dados["pw_filial"] = 1;
        if (!isset($dados["pro_fim"]) || $dados["pro_fim"] == "")
            $dados["pro_fim"] = NULL;
        if (!isset($dados["dt_validade"]) || $dados["dt_validade"] == "")
            $dados["dt_validade"] = NULL;
        if (!isset($dados["pro_unitario"]) || $dados["pro_unitario"] == "" || $dados["pro_unitario"] == NULL)
            $dados["pro_unitario"] = 0.00;
        if (!isset($dados["pro_qtd_estoque"]) || $dados["pro_qtd_estoque"] == "" || $dados["pro_qtd_estoque"] == NULL)
            $dados["pro_qtd_estoque"] = 0.00;
        if (!isset($dados["qtd_embalagem"]) || $dados["qtd_embalagem"] == "" || $dados["qtd_embalagem"] == NULL)
            $dados["qtd_embalagem"] = 0.00;
        if (!isset($dados["pes_bru"]) || $dados["pes_bru"] == "" || $dados["pes_bru"] == NULL)
            $dados["pes_bru"] = 0.00;
        if (!isset($dados["custo"]) || $dados["custo"] == "" || $dados["custo"] == NULL)
            $dados["custo"] = 0.00;
        if (!isset($dados["pes_liq"]) || $dados["pes_liq"] == "" || $dados["pes_liq"] == NULL)
            $dados["pes_liq"] = 0.00;
        if (!isset($dados["comprimento"]) || $dados["comprimento"] == "" || $dados["comprimento"] == NULL)
            $dados["comprimento"] = 0.00;
        if (!isset($dados["largura"]) || $dados["largura"] == "" || $dados["largura"] == NULL)
            $dados["largura"] = 0.00;
        if (!isset($dados["espessura"]) || $dados["espessura"] == "" || $dados["espessura"] == NULL)
            $dados["espessura"] = 0.00;
        if (!isset($dados["multiplo"]) || $dados["multiplo"] == "" || $dados["multiplo"] == NULL)
            $dados["multiplo"] = 0;
        if (!isset($dados["integra_web"]) || $dados["integra_web"] == "" || $dados["integra_web"] == NULL)
            $dados["integra_web"] = 1;
        if (!isset($dados["dt_alteracao"]) || $dados["dt_alteracao"] == "" || $dados["dt_alteracao"] == NULL)
            $dados["dt_alteracao"] = NULL;

        return $dados;
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial",
            "id_retaguarda",
            "id_fornecedor",
            "id_grupo",
            "id_subgrupo",
            "descricao",
            "cod_barras",
            "status",
            "unidvenda",
            "embalagem",
            "pro_inicio",
            "pro_fim",
            "qtd_embalagem",
            "pro_qtd_estoque",
            "pes_bru",
            "pes_liq",
            "ult_origem",
            "descto_verba",
            "aplicacao",
            "referencia",
            "ult_uf",
            "custo",
            "pro_unitario",
            "tipo_estoque",
            "dt_validade",
            "multiplo",
            "dt_alteracao",
            "integra_web",
            "pw_filial",
            "comprimento",
            "largura",
            "espessura",
            "meta_title",
            "meta_description",
            "meta_keywords",
            "frete_gratis",
            "descricao_curta",
            "ncm",
            "dun"
        );
    }
}