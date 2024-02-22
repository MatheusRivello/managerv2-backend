<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\CampanhaParticipante;
use App\Services\BaseService;
use Exception;

class CampanhaParticipanteServicoController extends BaseServicoController
{
    
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_CAMPANHA_PARTICIPANTE;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Campanhaparticipante' . CLASS_SERVICE;
        $this->entity = CampanhaParticipanteServicoController::class;
        $this->firstOrNew = ["id_campanha", "id_retaguarda"];
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
                "campanha" => $service->getAllIds("campanha")
            ];

            if (is_null($vetor["campanha"])) $erroValidacaoIds .= "(Campanha)";

            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $item['id_campanha'] = isset($vetor["campanha"][$item["id_filial"]][$item["id_campanha"]]) ? $vetor["campanha"][$item["id_filial"]][$item["id_campanha"]] : NULL;

                        if (is_null($item['id_campanha'])) $erroValidacaoIds = "(Campanha={$item["id_campanha"]} - Filial={$item["id_filial"]})";

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            unset($item["id_filial"]);
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
        return parent::store($dados, CampanhaParticipante::class, ["id_campanha", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, CampanhaParticipante::class, ["id_campanha", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_campanha", "id_retaguarda", "id_filial");
    }
}