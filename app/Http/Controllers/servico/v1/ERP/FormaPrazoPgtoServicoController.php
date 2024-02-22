<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\FormaPrazoPgto;
use App\Services\BaseService;
use Exception;

class FormaPrazoPgtoServicoController extends BaseServicoController
{
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_FORMA_PRAZO_PGTO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Formaprazopgto' . CLASS_SERVICE;
        $this->entity = FormaPrazoPgtoServicoController::class;
        $this->firstOrNew = ["id_forma_pgto", "id_prazo_pgto"];
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
                "formaPagamento" => $service->getAllId("forma_pagamento", TRUE),
                "prazoPagamento" => $service->getAllId("prazo_pagamento", TRUE)
            ];

            if (is_null($vetor["formaPagamento"])) $erroValidacaoIds = "(Forma Pagamento)";
            if (is_null($vetor["prazoPagamento"])) $erroValidacaoIds .= "(Prazo Pagamento)";

            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];

                    $idFormaPagamento = isset($vetor["formaPagamento"][$item["id_forma_pgto"]]) ? $vetor["formaPagamento"][$item["id_forma_pgto"]] : NULL;
                    $idPrazoPagamento = isset($vetor["prazoPagamento"][$item["id_prazo_pgto"]]) ? $vetor["prazoPagamento"][$item["id_prazo_pgto"]] : NULL;

                    if (is_null($idFormaPagamento)) $erroValidacaoIds = "(Forma pgto={$item["id_forma_pgto"]})";
                    if (is_null($idPrazoPagamento)) $erroValidacaoIds .= "(Prazo pgto={$item["id_prazo_pgto"]})";

                    if (!is_null($erroValidacaoIds)) {
                        $erros[] = "Nao existe - " . $erroValidacaoIds;
                        $erroValidacaoIds = NULL;
                    } else {
                        $vetorInsercao[] = [
                            "id_forma_pgto" => $idFormaPagamento,
                            "id_prazo_pgto" => $idPrazoPagamento
                        ];
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
        return parent::store($dados, FormaPrazoPgto::class, ["id_forma_pgto", "id_prazo_pgto"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, FormaPrazoPgto::class, ["id_forma_pgto", "id_prazo_pgto"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_forma_pgto", "id_prazo_pgto");
    }
}
