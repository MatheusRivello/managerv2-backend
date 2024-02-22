<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Services\BaseService;
use Exception;

class MixProdutoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_MIX_PRODUTO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Mixproduto' . CLASS_SERVICE;
        $this->entity = MixProdutoServicoController::class;
        $this->firstOrNew = ["id_filial", "id_produto", "id_cliente"];
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
                "produto" => $service->getAllIds("produto")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["cliente"])) $erroValidacaoIds = "(Cliente)";
            if (is_null($vetor["produto"])) $erroValidacaoIds .= "(Produto)";


            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];

                    $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                    $idProduto = isset($vetor["produto"][$item["id_filial"]][$item["id_produto"]]) ? $vetor["produto"][$item["id_filial"]][$item["id_produto"]] : NULL;
                    $idCliente = isset($vetor["cliente"][$item["id_filial"]][$item["id_cliente"]]) ? $vetor["cliente"][$item["id_filial"]][$item["id_cliente"]] : NULL;

                    if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                    if (is_null($idProduto)) $erroValidacaoIds .= "(Produto={$item["id_produto"]})";
                    if (is_null($idCliente)) $erroValidacaoIds .= "(Cliente={$item["id_cliente"]})";

                    if (!is_null($erroValidacaoIds)) {
                        $erros[] = "Nao existe - " . $erroValidacaoIds;
                        $erroValidacaoIds = NULL;
                    } else {
                        $totalInseridos++;
                        $vetorInsercao[] = [
                            "id_produto" => $idProduto,
                            "id_cliente" => $idCliente,
                            "qtd_minima" => $item["qtd_minima"],
                            "qtd_faturada" => $item["qtd_faturada"]
                        ];
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
        return parent::store($dados, MetaDetalhe::class, ["id_filial", "id_produto", "id_cliente"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, MetaDetalhe::class, ["id_filial", "id_produto", "id_cliente"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_produto", "id_cliente", "qtd_minima", "qtd_faturada");
    }
}
