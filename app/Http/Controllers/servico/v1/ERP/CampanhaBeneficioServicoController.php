<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\CampanhaBeneficio;
use App\Services\BaseService;
use Exception;

class CampanhaBeneficioServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_CAMPANHA_BENEFICIO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Campanhabeneficio' . CLASS_SERVICE;
        $this->entity = CampanhaBeneficioServicoController::class;
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
            
            $grupoLoaded = false;
            $subGrupoLoaded = false;
            $produtoLoaded = false;
            $fornecedorLoaded = false;

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

                        $codigo = NULL;

                        switch ($item['tipo']) {
                            case 0:
                                if (!$grupoLoaded) {
                                    $vetor['grupo'] = $service->getAllIds("grupo");
                                    $grupoLoaded = true;
                                }

                                $codigo = isset($vetor["grupo"][$item["id_filial"]][$item["codigo"]]) ? $vetor["grupo"][$item["id_filial"]][$item["codigo"]] : NULL;
                                break;
                            case 1:
                                if (!$subGrupoLoaded) {
                                    $vetor['subGrupo'] = $service->getAllIds("subgrupo", "id_retaguarda", "id", "id_grupo");
                                    $subGrupoLoaded = true;
                                }
                                $splitSIG = explode(".", $item['codigo']);
                                $codigo = isset($vetor["subGrupo"][$item["id_filial"]][$splitSIG[0]][$splitSIG[1]]) ? $vetor["subGrupo"][$item["id_filial"]][$splitSIG[0]][$splitSIG[1]] : NULL;
                                break;
                            case 2:
                                if (!$produtoLoaded) {
                                    $vetor['produto'] = $service->getAllIds("produto");
                                    $produtoLoaded = true;
                                }
                                $codigo = isset($vetor["produto"][$item["id_filial"]][$item["codigo"]]) ? $vetor["produto"][$item["id_filial"]][$item["codigo"]] : NULL;
                                break;
                            case 3:
                                if (!$fornecedorLoaded) {
                                    $vetor["fornecedor"] = $service->getAllIds("fornecedor");
                                    $fornecedorLoaded = true;
                                }
                                $codigo = isset($vetor["fornecedor"][$item["id_filial"]][$item["codigo"]]) ? $vetor["fornecedor"][$item["id_filial"]][$item["codigo"]] : NULL;
                                break;
                        }
                        
                        if (is_null($codigo)) {
                            $erroValidacaoIds = "(Campanha={$item["id_campanha"]} - Filial={$item["id_filial"]}) - Tipo: {$item["tipo"]} Codigo: {$item["codigo"]} - Não cadastrado";
                        } else {
                            $item['codigo'] = $codigo;
                        }

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
        return parent::store($dados, CampanhaBeneficio::class, ["id_campanha", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, CampanhaBeneficio::class, ["id_campanha", "id_retaguarda"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_campanha",
            "id_filial",
            "id_retaguarda",
            "tipo",
            "codigo",
            "quantidade",
            "percentual_desconto",
            "desconto_automatico",
            "bonificacao_automatica",
            "status"
        );
    }
}
