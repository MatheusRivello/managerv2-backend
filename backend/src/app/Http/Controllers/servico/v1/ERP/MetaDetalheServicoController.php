<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\MetaDetalhe;
use App\Services\BaseService;
use Exception;

class MetaDetalheServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_META_DETALHE;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Metadetalhe' . CLASS_SERVICE;
        $this->entity = MetaDetalheServicoController::class;
        $this->firstOrNew = ["id_filial", "id_meta", "ordem"];
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
                "meta" => $service->getAllIds("meta")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["meta"])) $erroValidacaoIds .= "(Meta)";

            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];
 
                    $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                    $idMeta = isset($vetor["meta"][$item["id_filial"]][$item["id_meta"]]) ? $vetor["meta"][$item["id_filial"]][$item["id_meta"]] : NULL;

                    if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                    if (is_null($idMeta)) $erroValidacaoIds .= "(Meta={$item["id_meta"]})";

                    if (!is_null($erroValidacaoIds)) {
                        $erros[] = "Nao existe - " . $erroValidacaoIds;
                        $erroValidacaoIds = NULL;
                    } else {
                        $item["id_meta"] = $idMeta;
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

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, MetaDetalhe::class, ["id_filial", "id_meta", "ordem"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, MetaDetalhe::class, ["id_filial", "id_meta", "ordem"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array(
            "id_filial", "id_meta", "ordem", "descricao", 
            "tot_cli_cadastrados", "tot_cli_atendidos", 
            "percent_tot_cli_atendidos", "tot_qtd_ven", "tot_peso_ven",
            "percent_tot_peso_ven", "tot_val_ven", "percent_tot_val_ven",
            "objetivo_vendas", "percent_atingido", "tendencia_vendas",
            "percent_tendencia_ven", "objetivo_clientes", "numero_cli_falta_atender",
            "ped_a_faturar", "prazo_medio", "percent_desconto", "tot_desconto"
        );
    }
}
