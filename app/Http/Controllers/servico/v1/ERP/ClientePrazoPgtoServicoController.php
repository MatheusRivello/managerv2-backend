<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\ClientePrazoPgto;
use App\Services\BaseService;
use Exception;

class ClientePrazoPgtoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_CLIENTE_PRAZO_PGTO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Clienteprazopgto' . CLASS_SERVICE;
        $this->entity = ClientePrazoPgtoServicoController::class;
        $this->firstOrNew = ["id_cliente", "id_prazo_pgto"];
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
                "prazoPgto" => $service->getAllId("prazo_pagamento", TRUE)
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds .= "(Filial)";
            if (is_null($vetor["cliente"])) $erroValidacaoIds .= "(Cliente)";
            if (is_null($vetor["prazoPgto"])) $erroValidacaoIds .= "(Prazo PGTO)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];
                       
                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idCliente = isset($vetor["cliente"][$item["id_filial"]][$item["id_cliente"]]) ? $vetor["cliente"][$item["id_filial"]][$item["id_cliente"]] : NULL;
                        $idPrazoPgto = isset($vetor["prazoPgto"][$item["id_retaguarda"]]) ? $vetor["prazoPgto"][$item["id_retaguarda"]] : NULL;

                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idCliente)) $erroValidacaoIds = "(Cliente={$item["id_cliente"]})";
                        if (is_null($idPrazoPgto)) $erroValidacaoIds = "(Prazo PGTO={$item["id_retaguarda"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            unset($item["id_filial"]);
                            unset($item["id_retaguarda"]);
                            $item["id_cliente"] = intval($idCliente);
                            $item["id_prazo_pgto"] = intval($idPrazoPgto);
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
        return parent::store($dados, ClientePrazoPgto::class, ["id_cliente", "id_prazo_pgto"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, ClientePrazoPgto::class, ["id_cliente", "id_prazo_pgto"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_retaguarda", "id_cliente", "id_prazo_pgto");
    }
}
