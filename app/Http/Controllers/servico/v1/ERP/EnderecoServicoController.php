<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Endereco;
use App\Services\BaseService;
use Exception;

class EnderecoServicoController extends BaseServicoController
{
    
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_ENDERECO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Endereco' . CLASS_SERVICE;
        $this->entity = EnderecoServicoController::class;
        $this->firstOrNew = ["id_retaguarda", "id_cliente"];
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
                "cidade" => $service->getAllId("cidade"),
                "cliente" => $service->getAllIds("cliente")
            ];

            if (is_null($vetor["filial"])) $erroValidacaoIds = "(Filial)";
            if (is_null($vetor["cidade"])) $erroValidacaoIds .= "(Cidade)";
            if (is_null($vetor["cliente"])) $erroValidacaoIds .= "(Cliente)";


            if (!is_null($erroValidacaoIds)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                for ($i = 0; $i < $totalRegistros; $i++) {
                    $validacao = $service->_validacao($array[$i], self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];

                        $idFilial = isset($vetor["filial"][$item["id_filial"]]) ? $vetor["filial"][$item["id_filial"]] : NULL;
                        $idCidade = isset($vetor["cidade"][$item["id_cidade"]]) ? $vetor["cidade"][$item["id_cidade"]] : NULL;
                        $idCliente = isset($vetor["cliente"][$item["id_filial"]][$item["id_cliente"]]) ? $vetor["cliente"][$item["id_filial"]][$item["id_cliente"]] : NULL;

                        if (is_null($idFilial)) $erroValidacaoIds = "(Filial={$item["id_filial"]})";
                        if (is_null($idCidade)) $erroValidacaoIds .= "(Cidade={$item["id_cidade"]})";
                        if (is_null($idCliente)) $erroValidacaoIds .= "(Cliente={$item["id_cliente"]})";//Id cliente retaguarda

                        if (!is_null($erroValidacaoIds)) {
                            $erros[] = "Nao existe - " . $erroValidacaoIds;
                            $erroValidacaoIds = NULL;
                        } else {
                            $item["id_cliente"] = intval($idCliente);
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
        return parent::store($dados, Endereco::class, ["id_retaguarda", "id_cliente"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Endereco::class, ["id_retaguarda", "id_cliente"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id_filial", "id_retaguarda", "tit_cod", "id_cliente", "cep", "logradouro", "numero", "complemento", "bairro", "uf",
            "latitude", "longitude", "id_cidade", "referencia", "rota_cod");
    }
}