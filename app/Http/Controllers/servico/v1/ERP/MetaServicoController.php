<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Meta;
use App\Services\BaseService;
use Exception;

class MetaServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_META;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Meta' . CLASS_SERVICE;
        $this->entity = MetaServicoController::class;
        $this->firstOrNew = ["id_filial", "id_vendedor", "id_retaguarda"];
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
                "filial" => $service->getAllId("filial")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["vendedor"])) $erroValidacaoIds .= "(Vendedor)";

            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];

                    $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                    $idVendedor = isset($vetor["vendedor"][$item["id_vendedor"]]) ? $vetor["vendedor"][$item["id_vendedor"]] : NULL;

                    if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                    if (is_null($idVendedor)) $erroValidacaoIds .= "(Vendedor={$item["id_vendedor"]})";

                    if (!is_null($erroValidacaoIds)) {
                        $erros[] = "Nao existe - " . $erroValidacaoIds;
                        $erroValidacaoIds = NULL;
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
        return parent::store($dados, Meta::class, ["id_filial", "id_vendedor", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Meta::class, ["id_filial", "id_vendedor", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial", "id_vendedor", "id_retaguarda",
            "descricao", "tot_qtd_ven", "tot_peso_ven",
            "objetivo_vendas", "tot_val_ven", "percent_atingido"
        );
    }
}
