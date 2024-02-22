<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Central\CabecalhoRequisicaoZip;
use App\Services\BaseService;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\DB;

class RoteiroServicoController extends BaseServicoController
{
    public $service;

    public function __construct(BaseService $service)
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_CABECALHO_REQ_ZIP;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Roteiro' . CLASS_SERVICE;
        $this->entity = RoteiroServicoController::class;
        $this->firstOrNew = ["token"];
        $this->acaoTabela = 1;

        $this->idEmpresa = $service->usuarioLogado()->fk_empresa;
    }

    public function novo(Request $request)
    {
        $array = isset($request->json) ? json_decode($request->json, true) : $request->all();

        $resposta = null;
        $vetor = [
            "tipo_requisicao" => $array["tipo_requisicao"],
            "token" => $this->idEmpresa . "-" . $array["token"],
            "metodo" => $array["metodo"],
            "fk_empresa" => $this->idEmpresa,
            "qtd_metodo" => $array["qtd"],
            "dt_inicio_envio_pacotes" => $array["data"]
        ];

        try {
            $validacao = $this->service->_validacao(self::_valoresNull($vetor), self::_nomeCamposDb());

            if (!empty($validacao["dados"])) {
                //Retorna o status da ultima sincronização seja ONLINE ou PROGRAMADA
                $ultimaSincronizacao = CabecalhoRequisicaoZip::select(
                    "status",
                    DB::raw("dt_inicio_envio_pacotes AS ultimoEnvio")
                )
                    ->where('fk_empresa', $this->idEmpresa)
                    ->whereIn('tipo_requisicao', ['2', '3'])
                    ->orderBy("dt_inicio_envio_pacotes", "DESC")
                    ->limit(1)
                    ->first();

                if (is_null($ultimaSincronizacao) || $ultimaSincronizacao["status"] == "") $ultimaSincronizacao["status"] = 3;

                $vetor["caminho"] = $this->service->_criarCaminhoRequisicaoZip($vetor["token"], $ultimaSincronizacao, $this->idEmpresa, "roteiro");

                if (self::inserirSemRetornoId($vetor)) {
                    $resposta = [
                        "code" => HTTP_OK,
                        "status" => "sucesso",
                    ];
                } else {
                    $resposta = [
                        "code" => HTTP_INTERNAL_SERVER_ERROR,
                        "status" => "erro",
                        "mensagem" => "Houve um imprevisto ao inserir"
                    ];
                }
            } else {
                $resposta[] = $validacao;
            }
        } catch (Exception $ex) {
            $resposta = $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }

        return response()->json($resposta, 200);
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, CabecalhoRequisicaoZip::class, ["token"], self::_nomeCamposDb());
    }

    public function atualizarInserirVetores($dados)
    {
        return parent::storeVetores($dados, CabecalhoRequisicaoZip::class, ["token"], self::_nomeCamposDb());
    }

    private function inserirSemRetornoId($dados)
    {
        $cabecalho = new CabecalhoRequisicaoZip();

        foreach ($dados as $campo => $valor) {
            $cabecalho->{$campo} = $valor;
        }

        if ($cabecalho->save()) {
            $resposta = true;
        } else {
            $resposta = false;
        }

        return $resposta;
    }

    protected function _valoresNull($dados)
    {
        if ($dados["qtd_metodo"] == "" || !isset($dados["qtd_metodo"])) $dados["qtd_metodo"] = 1;
        if ($dados["dt_inicio_envio_pacotes"] == "" || !isset($dados["dt_inicio_envio_pacotes"])) $dados["dt_inicio_envio_pacotes"] = date('Y-m-d H:i:s');

        return $dados;
    }

    public function _nomeCamposDb()
    {
        return array(
            "1tipo_requisicao", "token", "metodo",
            "fk_empresa", "qtd_metodo", "dt_inicio_envio_pacotes"
        );
    }
}
