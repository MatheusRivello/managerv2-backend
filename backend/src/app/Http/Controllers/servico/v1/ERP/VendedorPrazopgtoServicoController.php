<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\VendedorPrazo;
use App\Services\BaseService;
use Exception;

class VendedorPrazopgtoServicoController extends BaseServicoController
{
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_VENDEDOR_PRAZO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = self::_dadosClass()["metodo"];
        $this->entity = VendedorPrazopgtoServicoController::class;
        $this->firstOrNew = ["id_filial", "id_prazo_pgto", "id_vendedor"];
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
            $service = new BaseService();

            if (!is_null($erroPacote))
                $erros = $erroPacote;

            $vetor = [
                "filial" => $service->getAllId("filial"),
                "vendedor" => $service->getAllId("vendedor"),
                "prazoPgto" => $service->getAllId("prazo_pagamento", TRUE)
            ];

            if (is_null($vetor["filial"]))
                $erroValidacaoIds .= "(Filial)";
            if (is_null($vetor["vendedor"]))
                $erroValidacaoIds .= "(Vendedor)";
            if (is_null($vetor["prazoPgto"]))
                $erroValidacaoIds .= "(Prazo PGTO)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                foreach ($array as $key => $registro) {
                    $validacao = $service->_validacao($registro, self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idVendedor = isset($vetor["vendedor"][$item["id_vendedor"]]) ? $vetor["vendedor"][$item["id_vendedor"]] : NULL;
                        $idPrazoPgto = isset($vetor["prazoPgto"][$item["id_prazo_pgto"]]) ? $vetor["prazoPgto"][$item["id_prazo_pgto"]] : NULL;

                        if (is_null($idFilial))
                            $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idVendedor))
                            $erroValidacaoIds = "(Vendedor={$item["id_vendedor"]})";
                        if (is_null($idPrazoPgto))
                            $erroValidacaoIds = "(Prazo={$item["id_prazo_pgto"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            $item["id_vendedor"] = intval($idVendedor);
                            $item["id_prazo_pgto"] = $idPrazoPgto;
                            $vetorInsercao[] = $item;
                            $totalInseridos++;
                        }
                    } else {
                        $erros[] = $validacao;
                    }
                }

                unset($vetor);

                if (!is_null($vetorInsercao)) {
                    $retornoInsert = self::insertBatch($vetorInsercao);

                    if (!is_null($retornoInsert))
                        $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
                }
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function insertBatch($dados)
    {
        return parent::inserirVetoresApagaTabela($dados, VendedorPrazo::class, self::_dadosClass()["metodo"], self::_dadosClass()["acao"]);
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_prazo_pgto", "id_vendedor");
    }

    private function _dadosClass()
    {
        return array(
            "metodo" => 'Vendedorprazo' . CLASS_SERVICE,
            "acao" => 1
        );
    }


}