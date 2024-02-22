<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\FormaPagamento;
use App\Services\BaseService;
use Exception;

class FormaPagamentoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_FORMA_PAGAMENTO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Formapgto' . CLASS_SERVICE;
        $this->entity = FormaPagamentoServicoController::class;
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
                "formaPgto" => $service->getAllId("forma_pagamento", TRUE)
            ];

            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];
                   
                    $idFormaPgto = isset($vetor["formaPgto"][trim($item["id_retaguarda"])]) ? $vetor["formaPgto"][trim($item["id_retaguarda"])] : NULL;
                   
                    if (!is_null($idFormaPgto)) {
                        $concluido = self::atualizarInserir($item);

                        if ($concluido > 0 && !isset($concluido["erro"])) {
                            $totalatualizados++;
                        } else {
                            $erros[] = ['erro' => $concluido["erro"], 'id_retaguarda' => $item["id_retaguarda"]];
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
        return parent::store($dados, FormaPagamento::class, ["id_retaguarda"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, FormaPagamento::class, ["id_retaguarda"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_retaguarda", "descricao", "valor_min","situacao", "status");
    }
}