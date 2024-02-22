<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\VendedorProduto;
use App\Services\BaseService;
use Exception;

class VendedorProdutoServicoController extends BaseServicoController
{
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_VENDEDOR_PRODUTO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = self::_dadosClass()["metodo"];
        $this->entity = VendedorProdutoServicoController::class;
        $this->firstOrNew = ["id_produto", "id_filial", "id_vendedor"];
        $this->acaoTabela = self::_dadosClass()["acao"];
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

            if (!is_null($erroPacote))
                $erros = $erroPacote;

            $vetor = [
                "filial" => $service->getAllId("filial"),
                "vendedor" => $service->getAllId("vendedor"),
                "produto" => $service->getAllIds("produto")
            ];

            if (is_null($vetor["vendedor"]))
                $erroValidacaoIds = "(Vendedor)";
            if (is_null($vetor["produto"]))
                $erroValidacaoIds .= "(Produto)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                    foreach ($array as $key => $registro) {
                    $validacao = $service->_validacao($registro, self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idProduto = isset($vetor["produto"][$item["id_filial"]][$item["id_produto"]]) ? $vetor["produto"][$item["id_filial"]][$item["id_produto"]] : NULL;
                        $idVendedor = isset($vetor["vendedor"][$item["id_vendedor"]]) ? $vetor["vendedor"][$item["id_vendedor"]] : NULL;

                        if (is_null($idFilial))
                            $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idProduto))
                            $erroValidacaoIds .= "(Produto={$item["id_produto"]})";
                        if (is_null($idVendedor))
                            $erroValidacaoIds .= "(Vendedor={$item["id_vendedor"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            $vetorInsercao[] = [
                                "id_vendedor" => intval($item["id_vendedor"]),
                                "id_produto" => intval($idProduto)
                            ];

                            $totalInseridos++;
                            unset($registro);
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
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function insertBatch($dados)
    {
        return parent::inserirVetoresApagaTabela($dados, VendedorProduto::class, self::_dadosClass()["metodo"], self::_dadosClass()["acao"]);
    }

    public function _nomeCamposDb()
    {
        return array("id_produto", "id_filial", "id_vendedor");
    }

    private function _dadosClass()
    {
        return array(
            "metodo" => 'Vendedorproduto' . CLASS_SERVICE,
            "acao" => 1
        );
    }
}
