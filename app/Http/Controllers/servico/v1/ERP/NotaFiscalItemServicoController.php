<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\NotaFiscalItem;
use App\Services\BaseService;
use Exception;

class NotaFiscalItemServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_NOTA_FISCAL_ITEM;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = self::_dadosClass()["metodo"];
        $this->entity = NotaFiscalServicoController::class;
        $this->firstOrNew = ["id_filial", "nfs_doc", "nfs_serie"];
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
                "notaFiscal" => $service->getAllIds("nota_fiscal", "ped_num", "ped_num"),
                "produto" => $service->getAllIds("produto")
            ];

            if (is_null($vetor["filial"]))
                $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["produto"]))
                $erroValidacaoIds .= "(produto)";
            if (is_null($vetor["notaFiscal"]))
                $erroValidacaoIds .= "(Nota Fiscal)";

            if (is_null($vetor["filial"]))
                $erroValidacaoIds = "(Filial)";


            for ($i = 0; $i < $totalRegistros; $i++) {

                $validacao = $service->_validacao(self::_valoresNull($array[$i]), self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];

                    $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                    $idProduto = isset($vetor["produto"][$item["id_filial"]][$item["id_produto"]]) ? $vetor["produto"][$item["id_filial"]][$item["id_produto"]] : NULL;
                    $idNotaFiscal = isset($vetor["notaFiscal"][$item["id_filial"]][$item["ped_num"]]) ? $vetor["notaFiscal"][$item["id_filial"]][$item["ped_num"]] : NULL;

                    if (is_null($idFilial))
                        $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                    if (is_null($idProduto))
                        $erroValidacaoIds .= "(Produto={$item["id_produto"]})";
                    if (is_null($idNotaFiscal))
                        $erroValidacaoIds .= "(Nota Fiscal={$item["ped_num"]})";

                    if (!is_null($erroValidacaoIds)) {
                        $erros[] = "Nao existe - " . $erroValidacaoIds;
                        $erroValidacaoIds = NULL;
                    } else {
                        $item["id_produto"] = intval($idProduto);
                        unset($item["id_cliente"]);
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
                    $erros[] = ["erro" => PACOTE_NAO_INSERIDO, "data" => $vetorInsercao];
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function insertBatch($dados)
    {
        return parent::inserirVetoresApagaTabela($dados, NotaFiscalItem::class, self::_dadosClass()["metodo"], self::_dadosClass()["acao"]);
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial",
            "ped_num",
            "id_produto",
            "nfs_qtd",
            "nfs_doc",
            "nfs_serie",
            "nfs_unitario",
            "nfs_desconto",
            "nfs_descto",
            "nfs_total",
            "ped_qtd",
            "ped_total",
            "nfs_status",
            "nfs_custo",
            "nfs_peso"
        );
    }

    protected function _valoresNull($dados)
    {
        if (!isset($dados["nfs_doc"]) || $dados["nfs_doc"] == "")
            $dados["nfs_doc"] = NULL;
        if (!isset($dados["nfs_serie"]) || $dados["nfs_serie"] == "")
            $dados["nfs_serie"] = NULL;
        if (!isset($dados["nfs_emissao"]) || $dados["nfs_emissao"] == "")
            $dados["nfs_emissao"] = NULL;

        return $dados;
    }

    public function _dadosClass()
    {
        return array(
            "metodo" => 'Notafiscalitem' . CLASS_SERVICE,
            "acao" => 0
        );
    }

}