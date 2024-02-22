<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\TituloFinanceiro;
use App\Services\BaseService;
use Exception;

class TituloFinanceiroServicoController extends BaseServicoController
{
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_TITULO_FINANCEIRO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Titulofinanceiro' . CLASS_SERVICE;
        $this->entity = TituloFinanceiroServicoController::class;
        $this->firstOrNew = ["id_filial","id_retaguarda", "numero_doc"];
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
                "formaPgto" => $service->getAllId("forma_pagamento", TRUE)
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["cliente"])) $erroValidacaoIds .= "(Cliente)";
            if (is_null($vetor["formaPgto"])) $erroValidacaoIds .= "(Forma pgto)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao(self::_valoresNull($array[$i]), self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idCliente = isset($vetor["cliente"][$item["id_filial"]][$item["id_cliente"]]) ? $vetor["cliente"][$item["id_filial"]][$item["id_cliente"]] : NULL;
                        $idFormaPgto = isset($vetor["formaPgto"][$item["id_forma_pgto"]]) ? $vetor["formaPgto"][$item["id_forma_pgto"]] : NULL;

                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idCliente)) $erroValidacaoIds .= "(Cliente={$item["id_cliente"]})";
                        if (is_null($idFormaPgto)) $erroValidacaoIds .= "(FormaPgto={$item["id_forma_pgto"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            unset($item["id_filial"]);
                            $item["id_cliente"] = intval($idCliente);
                            $item["id_forma_pgto"] = intval($idFormaPgto);

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
        return parent::store($dados, TituloFinanceiro::class, ["id_filial","id_retaguarda", "numero_doc"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, TituloFinanceiro::class, ["id_filial","id_retaguarda", "numero_doc"], self::_nomeCamposDb());
    }


    protected function _valoresNull($dados)
    {
        if (!isset($dados["valor"]) || $dados["valor"] == "" || is_null($dados["valor"])) $dados["valor"] = 0.00;
        if (!isset($dados["multa_juros"]) || $dados["multa_juros"] == "" || is_null($dados["multa_juros"])) $dados["multa_juros"] = 0.00;
        if (!isset($dados["dt_pagamento"]) || $dados["dt_pagamento"] == "") $dados["dt_pagamento"] = NULL;
        if (!isset($dados["dt_competencia"]) || $dados["dt_competencia"] == "") $dados["dt_competencia"] = NULL;
        if (!isset($dados["dt_vencimento_orig"]) || $dados["dt_vencimento_orig"] == "") $dados["dt_vencimento_orig"] = NULL;
        if (!isset($dados["valor_original"]) || $dados["valor_original"] == "") $dados["valor_original"] = 0;

        return $dados;
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial", "id_cliente", "id_forma_pgto", "descricao", "id_retaguarda", "numero_doc", "tipo_titulo", "parcela",
            "valor", "multa_juros", "status", "dt_pagamento", "dt_competencia", "dt_emissao", "dt_vencimento", "valor_original", "linha_digitavel", "id_vendedor", "nosso_numero", "valor_devolucao", "dt_vencimento_orig"
        );
    }
}
