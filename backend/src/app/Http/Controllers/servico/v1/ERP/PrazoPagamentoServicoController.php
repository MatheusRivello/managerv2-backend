<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\PrazoPagamento;
use App\Services\BaseService;
use Exception;

class PrazoPagamentoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PRAZO_PAGAMENTO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Prazopgto' . CLASS_SERVICE;
        $this->entity = PrazoPagamentoServicoController::class;
        $this->firstOrNew = ["id_retaguarda"];
        $this->acaoTabela = 2;
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
                "prazoPgto" => $service->getAllId("prazo_pagamento", TRUE)
            ];

            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];

                    $idPrazoPgto = isset($vetor["prazoPgto"][$item["id_retaguarda"]]) ? $vetor["prazoPgto"][$item["id_retaguarda"]] : NULL;
                    if (!is_null($idPrazoPgto)) {
                        
                        $concluido = self::atualizarInserir($item);
                        
                        if ($concluido > 0 || $concluido != "") {
                            $totalatualizados++;
                        } else {
                            $erros[] = ["erro" => ERRO_ATUALIZAR, "id_retaguarda" => $item["id_retaguarda"]];
                        }
                    } else {
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
        return parent::store($dados, PrazoPagamento::class, ["id_retaguarda"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, PrazoPagamento::class, ["id_retaguarda"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_retaguarda", "descricao", "variacao","valor_min", "status");
    }
}
