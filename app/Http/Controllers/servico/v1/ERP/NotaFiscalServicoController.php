<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\NotaFiscal;
use App\Services\BaseService;
use Exception;

class NotaFiscalServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_NOTA_FISCAL;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Notafiscal' . CLASS_SERVICE;
        $this->entity = NotaFiscalServicoController::class;
        $this->firstOrNew = ["id_filial", "nfs_doc", "nfs_serie"];
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
                "vendedor" => $service->getAllId("vendedor"),
                "cliente" => $service->getAllIds("cliente")
            ];

            if (is_null($vetor["vendedor"])) $erroValidacaoIds = "(Vendedor)";
            if (is_null($vetor["cliente"])) $erroValidacaoIds .= "(Cliente)";


            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";


            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao(self::_valoresNull($array[$i]), self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];

                    $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                    $idCliente = isset($vetor["cliente"][$item["id_filial"]][$item["id_cliente"]]) ? $vetor["cliente"][$item["id_filial"]][$item["id_cliente"]] : NULL;
                    $idVendedor = isset($vetor["vendedor"][$item["id_vendedor"]]) ? $vetor["vendedor"][$item["id_vendedor"]] : NULL;

                    if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                    if (is_null($idCliente)) $erroValidacaoIds .= "(Cliente={$item["id_cliente"]})";
                    if (is_null($idVendedor)) $erroValidacaoIds .= "(Vendedor={$item["id_vendedor"]})";

                    if (!is_null($erroValidacaoIds)) {
                        $erros[] = "Nao existe - " . $erroValidacaoIds;
                        $erroValidacaoIds = NULL;
                    } else {
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

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, NotaFiscal::class, ["id_filial", "nfs_doc", "nfs_serie"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, NotaFiscal::class, ["id_filial", "nfs_doc", "nfs_serie"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial", "id_cliente", "id_vendedor",
            "nfs_doc", "nfs_serie", "nfs_emissao",
            "nfs_valbrut", "nfs_tipo", "ped_num",
            "ped_entrega", "prazo_pag", "forma_pag",
            "nfs_status", "ped_emissao", "ped_total",
            "nfs_custo", "observacao", "xml", "chavenf",
            "nfs_peso"
        );
    }
    
    protected function _valoresNull($dados)
    {
        if (!isset($dados["nfs_emissao"]) || $dados["nfs_emissao"] == "") $dados["nfs_emissao"] = NULL;
        if (!isset($dados["nfs_doc"]) || $dados["nfs_doc"] == "") $dados["nfs_doc"] = NULL;
        if (!isset($dados["nfs_serie"]) || $dados["nfs_serie"] == "") $dados["nfs_serie"] = NULL;

        return $dados;
    }
}
