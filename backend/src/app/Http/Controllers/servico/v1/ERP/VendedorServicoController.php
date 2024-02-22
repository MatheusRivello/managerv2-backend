<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Central\Dispositivo;
use App\Models\Tenant\Vendedor;
use App\Services\BaseService;
use Exception;

class VendedorServicoController extends BaseServicoController
{
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_VENDEDOR;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Vendedor' . CLASS_SERVICE;
        $this->entity = VendedorServicoController::class;
        $this->firstOrNew = ["id"];
        $this->acaoTabela = 0;
        $this->customCasts = ["id_pedido", "numero_item"];
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
            $idEmpresa = $service->usuarioLogado()->fk_empresa;

            if (!is_null($erroPacote)) $erros = $erroPacote;

            $vetor = [
                "vendedor" => $service->getAllId("vendedor")
            ];

            if (!is_null($erroPacote)) {
                $erros[] = "Nao existe nenhum registro na tabela - {$erroValidacaoIds}";
            } else {
                foreach ($array as $key => $vendedor) {

                    $validacao = $service->_validacao($vendedor, self::_nomeCamposDb());

                    if ($validacao["status"] == "sucesso") {
                        $item = $validacao["dados"];
                        if (empty($item)) continue;
                        $idVendedor = isset($vetor["vendedor"]) ? (isset($vetor["vendedor"][$item["id"]]) ? $item["id"] : NULL) : NULL;

                        //Caso o vendedor estiver desativado no ERP, imediatamente ele desativará o dispositivo que estiver relacionado a ele
                        if (!is_null($idVendedor)) {
                            if (intval($item["status"]) == 0) self::atualizaStatusDipositivo($idEmpresa, $idVendedor);
                            $vetorAtualizacao[] = $item;
                            $totalatualizados++;
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

                    if (!is_null($retornoInsert)) $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
                }

                if (isset($vetorAtualizacao)) {
                    $retornoAtualizacao = self::atualizarBatch($vetorAtualizacao);

                    if (!is_null($retornoAtualizacao)) $erros[] = ["erro" => PACOTE_NAO_INSERIDO];
                }
            }

            return parent::_informarMensagemRetorno($totalRegistros, $totalInseridos, $totalatualizados, $erros);
        } catch (Exception $ex) {
            return $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function insertBatch($dados)
    {
        return parent::inserirVetores($dados, Vendedor::class);
    }

    private function atualizarBatch($dados)
    {
        return parent::updateVetores($dados, Vendedor::class, "id", self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array(
            "id", "nome",
            "status", "senha",
            "supervisor", "gerente",
            "tipo", "saldoverba"
        );
    }

    public function atualizaStatusDipositivo($idEmpresa, $idVendedor)
    {
        $dispositivo = Dispositivo::where("fk_empresa", $idEmpresa)
            ->where('id_vendedor', $idVendedor)
            ->first();

        if (isset($dispositivo)) {
            $dispositivo->status = FALSE;
            $dispositivo->licenca = LICENCA_BLOQUEADA;
            $dispositivo->save();
        } else {
            return false;
        }
    }
}
