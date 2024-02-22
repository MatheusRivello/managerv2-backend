<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Central\ServicoLocal;
use App\Services\BaseService;
use Exception;

class ServicoLocalServicoController extends BaseServicoController
{
    public function __construct(BaseService $service)
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_SERVICO_LOCAL;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Servicolocal' . CLASS_SERVICE;
        $this->entity = ServicoLocalServicoController::class;
        $this->firstOrNew = ["fk_empresa"];
        $this->acaoTabela = 0;

        $this->idEmpresa = $service->usuarioLogado()->fk_empresa;
    }

    public function servicolocal()
    {
        $vetor = [
            "fk_empresa" => $this->idEmpresa,
            "dt_atualizado" => date("Y-m-d H:i:s")
        ];

        try {
            $concluido = self::atualizarInserir($vetor);

            if ($concluido) {
                $resposta = [
                    "code" => HTTP_OK,
                    "status" => "sucesso"
                ];
            } else {
                $resposta = [
                    "code" => HTTP_INTERNAL_SERVER_ERROR,
                    "status" => "erro",
                    "mensagem" => "Houve um imprevisto ao inserir"
                ];
            }

            return response()->json($resposta, $resposta['code']);
        } catch (Exception $ex) {
            return  $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, ServicoLocal::class, ["fk_empresa"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("fk_empresa", "dt_atualizado");
    }
}
