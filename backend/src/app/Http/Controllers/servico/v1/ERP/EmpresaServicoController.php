<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Central\Empresa;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;


class EmpresaServicoController extends BaseServicoController
{
    public $service;

    public function __construct(BaseService $service)
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_EMPRESA;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Empresa' . CLASS_SERVICE;
        $this->entity = EmpresaServicoController::class;
        $this->firstOrNew = ["id"];
        $this->acaoTabela = 0;
        $this->idEmpresa = $service->usuarioLogado()->fk_empresa;
    }

    public function verificaAtualizacao()
    {
        $dados = $this->getDadosEmpresa();

        switch (intval($dados->atualizar_sincronizador)) {
            case 0:
                $resposta = '{result:[false]}';
                break;
            case 1:
                $resposta = '{result:[true]}';
                break;
            default:
                $resposta = '{result:[null]}';
        }

        return response()->json($resposta, HTTP_OK);
    }


    public function atualizarEmpresa(Request $request)
    {
        $atualizarSincronizador = isset($request->atualizar_sincronizador) ? $request->atualizar_sincronizador : 1;
        $vetor = [
            "id" => $this->idEmpresa,
            "atualizar_sincronizador" => $atualizarSincronizador,
            "dt_versao_sincronizador_atualizado" => date("Y-m-d H:i:s")
        ];

        //se concluir ele atualiza a versão
        if ($request->atualizar_sincronizador == 0) {
            $vetor["versao_sincronizador"] = $request->versao_sincronizador;
        } else {
            $vetor["versao_sincronizador"] = $this->getDadosEmpresa()->versao_sincronizador;
        }

        try {
            $resultado = $this->atualizarInserir($vetor);

            $resposta = !isset($resultado['erro']) ? [
                "code" => HTTP_ACCEPTED,
                "status" => "successo",
                "mensagem" => REGISTRO_SALVO
            ] : [
                "code" => HTTP_NOT_ACCEPTABLE,
                "status" => "error",
                "mensagem" => ERRO_NA_CONSULTA
            ];

            return response()->json($resposta, $resposta["code"]);
        } catch (Exception $ex) {
            return  $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, Empresa::class, ["id"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, Empresa::class, ["id"], self::_nomeCamposDb());
    }

    public function _nomeCamposDb()
    {
        return array("id", "versao_sincronizador", "atualizar_sincronizador");
    }

    public function getDadosEmpresa()
    {
        $empresa = Empresa::find($this->idEmpresa);

        return $empresa;
    }
}
