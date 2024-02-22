<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\VendedorProtabelapreco;
use App\Services\BaseService;
use Exception;

class VendedorTabelaPrecoServicoController extends BaseServicoController
{
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_VENDEDOR_PROTABELA_PRECO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Vendedorprotabelapreco' . CLASS_SERVICE;
        $this->entity = VendedorTabelaPrecoServicoController::class;
        $this->firstOrNew = ["id_filial", "id_vendedor", "id_tabela"];
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
                "vendedor" => $service->getAllId("vendedor"),
                "proTabelaPreco" => $service->getAllIds("protabela_preco")
            ];

            if (is_null($vetor["vendedor"])) $erroValidacaoIds = "(Vendedor)";
            if (is_null($vetor["proTabelaPreco"])) $erroValidacaoIds .= "(Pro Tabela Preco)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["proTabelaPreco"][$item["id_filial"]]) ? $vetor["proTabelaPreco"][$item["id_filial"]] : NULL;
                        $idTabela = isset($vetor["proTabelaPreco"][$item["id_filial"]][$item["id_tabela"]]) ? $vetor["proTabelaPreco"][$item["id_filial"]][$item["id_tabela"]] : NULL;
                        $idVendedor = isset($vetor["vendedor"][$item["id_vendedor"]]) ? $vetor["vendedor"][$item["id_vendedor"]] : NULL;

                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idTabela)) $erroValidacaoIds .= "(Tabela Preco={$item["id_tabela"]})";
                        if (is_null($idVendedor)) $erroValidacaoIds .= "(Vendedor={$item["id_vendedor"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            $vetorInsercao[] = [
                                "id_vendedor" => intval($item["id_vendedor"]),
                                "id_protabela_preco" => intval($idTabela)
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
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, VendedorProtabelapreco::class, ["id_filial", "id_vendedor", "id_tabela"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, VendedorProtabelapreco::class, ["id_filial", "id_vendedor", "id_tabela"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_vendedor", "id_tabela");
    }
}
