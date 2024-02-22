<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Contato;
use App\Services\BaseService;
use Exception;

class ClienteContatoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_CONTATO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = self::_dadosClass()["metodo"];
        $this->entity = ClienteContatoServicoController::class;
        $this->firstOrNew = ["id_cliente", "con_cod"];
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
                "cliente" => $service->getAllIds("cliente"),
            ];

            if (is_null($vetor["filial"]))
                $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["cliente"]))
                $erroValidacaoIds = "(Cliente)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idCliente = isset($vetor["cliente"][$item["id_filial"]][$item["id_cliente"]]) ? $vetor["cliente"][$item["id_filial"]][$item["id_cliente"]] : NULL;

                        if (is_null($idFilial))
                            $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idCliente))
                            $erroValidacaoIds = "(Cliente={$item["id_cliente"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            $item["id_cliente"] = intval($idCliente);
                            unset($item["id_filial"]);

                            $vetorInsercao[] = $item;
                            $totalInseridos++;
                        }
                    } else {
                        $erros[] = $validacao;
                    }
                }

                if (!is_null($vetorInsercao)) {
                    $retornoInsert = self::insertBatch($vetorInsercao);
                    
                    if (is_null($retornoInsert))
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
        return parent::inserirVetoresApagaTabela($dados, Contato::class, self::_dadosClass()["metodo"], self::_dadosClass()["acao"]);
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_cliente", "cnpj", "telefone", "email", "nome", "aniversario", "hobby", "con_cod");
    }

    public function _dadosClass()
    {
        return array(
            "metodo" => 'Contato' . CLASS_SERVICE,
            "acao" => 1
        );
    }
}