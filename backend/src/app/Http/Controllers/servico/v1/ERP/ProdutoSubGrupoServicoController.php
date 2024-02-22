<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Subgrupo;
use App\Services\BaseService;
use Exception;

class ProdutoSubGrupoServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_SUBGRUPO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Produtosubgrupo' . CLASS_SERVICE;
        $this->entity = ProdutoSubGrupoServicoController::class;
        $this->firstOrNew = ["id_filial", "id_retaguarda", "id_grupo"];
        $this->acaoTabela = 0;
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
                "grupo" => $service->getAllIds("grupo"),
                "subGrupo" => $service->getAllIds("subgrupo", "id_retaguarda", "id", "id_grupo")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["grupo"])) $erroValidacaoIds .= "(Grupo)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idGrupo = isset($vetor["grupo"][$item["id_filial"]][$item["id_grupo"]]) ? $vetor["grupo"][$item["id_filial"]][$item["id_grupo"]] : NULL;
                        $idSubGrupo = isset($vetor["subGrupo"][$item["id_filial"]][$item["id_grupo"]][$item["id_retaguarda"]]) ? $vetor["subGrupo"][$item["id_filial"]][$item["id_grupo"]][$item["id_retaguarda"]] : NULL;

                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idGrupo)) $erroValidacaoIds .= "(Grupo={$item["id_grupo"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {

                            if (!is_null($idSubGrupo)) {
                                $concluido = self::atualizarInserir($item);

                                if ($concluido > 0) {
                                    $totalatualizados++;
                                } else {
                                    $erros[] = ["erro" => ERRO_ATUALIZAR, "id_retaguarda" => $item["id_retaguarda"]];
                                }
                            } else {
                                $vetorInsercao[] = $item;
                                $totalInseridos++;
                            }
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
        return parent::store($dados, Subgrupo::class, ["id_filial", "id_retaguarda", "id_grupo"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Subgrupo::class, ["id_filial", "id_retaguarda", "id_grupo"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial", "id_retaguarda", "id_grupo",
            "subgrupo_desc", "descto_max", "status"
        );
    }
}
