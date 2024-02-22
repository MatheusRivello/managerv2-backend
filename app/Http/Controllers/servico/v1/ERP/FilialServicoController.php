<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Filial;
use App\Services\BaseService;
use Exception;

class FilialServicoController extends BaseServicoController
{

    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_FILIAL;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Filial' . CLASS_SERVICE;
        $this->entity = FilialServicoController::class;
        $this->firstOrNew = ['emp_cgc'];
    }

    public static function atualizarDados($array, $erroPacote)
    {
        try {
            $erros = NULL;
            $vetorInsercao = NULL;
            $totalInseridos = 0;
            $totalAtualizados = 0;
            $totalRegistros = count($array);
            $erroValidacaoIds = NULL;
            $service = new BaseService();

            if (!is_null($erroPacote))
                $erros = $erroPacote;

            $vetor = [
                "filial" => $service->getAllId("filial")
            ];

            for ($i = 0; $i < $totalRegistros; $i++) {
                $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                if ($validacao["status"] == "sucesso") {
                    $item = $validacao["dados"];
                    $item["emp_cgc"] = str_replace("//", "/", $item["emp_cgc"]);
                    $idFilial = isset($vetor["filial"][$item["id"]]) ? $vetor["filial"][$item["id"]] : NULL;

                    if (!is_null($idFilial)) {
                        $vetorAtualizacao[] = $item;
                        $totalAtualizados++;
                    } else {
                        $vetorInsercao[] = $item;
                        $totalInseridos++;
                    }
                } else {
                    $erros[] = $validacao;
                }
            }

            if (isset($vetorInsercao)) {
                $retornoInsert = self::insertBatch($vetorInsercao);

                if (!is_null($retornoInsert))
                    $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
            }

            if (isset($vetorAtualizacao)) {
                $retornoAtualizacao = self::atualizarBatch($vetorAtualizacao);
                if (!is_null($retornoAtualizacao))
                    $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
            }
            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalAtualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function insertBatch($dados)
    {
        return parent::inserirVetores($dados, Filial::class);
    }
    private function atualizarBatch($dados)
    {
        return parent::updateVetores($dados, Filial::class, "id", self::_nomeCamposDb());
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, Filial::class, ['id'], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Filial::class, ['id'], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id", "emp_cgc", "emp_raz", "emp_fan", "emp_ativa", "emp_uf", "emp_email");
    }
}