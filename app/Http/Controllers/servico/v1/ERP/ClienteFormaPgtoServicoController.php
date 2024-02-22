<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\ClienteFormaPgto;
use App\Services\BaseService;
use Exception;

class ClienteFormaPgtoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_CLIENTE_FORMA_PGTO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = self::_dadosClass()["metodo"];
        $this->entity = ClienteFormaPgtoServicoController::class;
        $this->firstOrNew = ["id_cliente", "id_forma_pgto"];
        $this->acaoTabela = self::_dadosClass()["acao"];
    }

    public function atualizarDados($array, $erroPacote)
    {
        try {
            $erros = NULL;
            $vetorInsercao = NULL;
            $totalInseridos = 0;
            $totalatualizados = 0;
            $totalRegistros = count($array);
            $erroValidacaoIds = NULL;

            if (!is_null($erroPacote))
                $erros = $erroPacote;

            $vetor = [
                "filial" => $this->service->getAllId("filial"),
                "cliente" => $this->service->getAllIds("cliente"),
                "formaPgto" => $this->service->getAllId("forma_pagamento", TRUE)
            ];

            if (is_null($vetor["filial"]))
                $erroValidacaoIds .= "(Filial)";
            if (is_null($vetor["cliente"]))
                $erroValidacaoIds .= "(Cliente)";
            if (is_null($vetor["formaPgto"]))
                $erroValidacaoIds .= "(Forma PGTO)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                foreach ($array as $key => $dado) {
                    $validacao = $this->service->_validacao($dado, self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idCliente = isset($vetor["cliente"][$item["id_filial"]][$item["id_cliente"]]) ? $vetor["cliente"][$item["id_filial"]][$item["id_cliente"]] : NULL;
                        $idFormaPgto = isset($vetor["formaPgto"][$item["id_retaguarda"]]) ? $vetor["formaPgto"][$item["id_retaguarda"]] : NULL;

                        if (is_null($idFilial))
                            $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idCliente))
                            $erroValidacaoIds = "(Cliente={$item["id_cliente"]})";
                        if (is_null($idFormaPgto))
                            $erroValidacaoIds = "(Forma PGTO={$item["id_retaguarda"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            unset($item["id_filial"]);
                            unset($item["id_retaguarda"]);
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
                    $retornoInsert = self::insertBatch($vetorInsercao);

                    if (!is_null($retornoInsert))
                        $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
                }
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function insertBatch($dados)
    {
        return parent::inserirVetoresApagaTabela($dados, ClienteFormaPgto::class, self::_dadosClass()["metodo"], self::_dadosClass()["acao"]);
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_retaguarda", "id_cliente", "id_forma_pgto");
    }

    public function _dadosClass()
    {
        return array(
            "metodo" => 'Clienteformapgto' . CLASS_SERVICE,
            "acao" => 1
        );
    }

}