<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Services\BaseService;
use Exception;

class CampanhaServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_CAMPANHA;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Campanha' . CLASS_SERVICE;
        $this->entity = CampanhaServicoController::class;
        $this->firstOrNew = ["id_filial", "id_retaguarda"];
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
                "filial" => $service->getAllId("filial")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao(self::_valoresNull($array[$i]), self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;

                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";

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
            }
            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, Contato::class, ["id_filial", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Contato::class, ["id_filial", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial",
            "id_retaguarda",
            "descricao",
            "tipo_modalidade",
            "data_inicial",
            "data_final",
            "mix_minimo",
            "valor_minimo",
            "valor_maximo",
            "volume_minimo",
            "volume_maximo",
            "qtd_max_bonificacao",
            "permite_acumular_desconto",
            "status"
        );
    }

    protected function _valoresNull($dados)
    {
        if ($dados["permite_acumular_desconto"] == NULL) {
            $dados["permite_acumular_desconto"] = 0;
        }

        return $dados;
    }
}
