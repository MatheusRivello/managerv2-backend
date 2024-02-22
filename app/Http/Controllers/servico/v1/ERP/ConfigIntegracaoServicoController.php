<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Central\ConfiguracaoEmpresa;
use App\Models\Central\PeriodoSincronizacao;
use Illuminate\Support\Facades\DB;

class ConfigIntegracaoServicoController extends BaseServicoController
{
    public function __construct()
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_CONFIGURACAO_EMPRESA;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Confintegracao' . CLASS_SERVICE;
        $this->entity = ConfigIntegracaoServicoController::class;
        $this->firstOrNew = ["fk_empresa", "fk_tipo_configuracao_empresa", "tipo"];
        $this->acaoTabela = 0;
        $this->customCasts = [
            "ConfigEmpresa" => ["id", "tipo", "tipo_ordem"],
            "PeriodoSinc" => ["id"]
        ];
        $this->idEmpresa = $this->service->usuarioLogado()->fk_empresa;
    }


    /**
     * Retorna a lista dos novas configurações
     */
    public function getNovasConfigs()
    {
        $data = [];
        $configEmpresa = ConfiguracaoEmpresa::class;
        $periodoSinc = PeriodoSincronizacao::class;

        $data["dados"] = $configEmpresa::select(
            "tipo_configuracao_empresa.id",
            "tipo_configuracao_empresa.label",
            "configuracao_empresa.tipo",
            "configuracao_empresa.valor",
            DB::raw("tipo_configuracao_empresa.tipo as tipo_ordem")
        )
            ->withCasts($this->util->convertValueJSON($configEmpresa, $this->customCasts["ConfigEmpresa"]))
            ->where('configuracao_empresa.fk_empresa', $this->idEmpresa)
            ->join("tipo_configuracao_empresa", function ($query) {
                $query->on("configuracao_empresa.fk_tipo_configuracao_empresa", "=", "tipo_configuracao_empresa.id");
                $query->on("configuracao_empresa.fk_empresa", "=", "tipo_configuracao_empresa.fk_empresa");
            })
            ->get();

        $data["configuracao"] = $periodoSinc::where("fk_empresa", $this->idEmpresa)
            ->withCasts($this->util->convertValueJSON($periodoSinc, $this->customCasts["PeriodoSinc"]))
            ->get();

        $resposta = (is_null($data)) ? [
            'status' => 'erro',
            'code' => HTTP_NOT_FOUND,
            'mensagem' => ERRO_REGISTRO_NAO_LOCALIZADO
        ] : [
            'status' => 'sucesso',
            'code' => HTTP_ACCEPTED,
            'data' => $data
        ];

        return response()->json($resposta, $resposta["code"]);
    }

    public function _nomeCamposDb()
    {
        return array(
            'fk_empresa',
            'fk_tipo_configuracao_empresa',
            'tipo',
            'valor'
        );
    }
}
