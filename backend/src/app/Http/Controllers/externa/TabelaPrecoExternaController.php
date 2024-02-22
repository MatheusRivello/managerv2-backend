<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\ProtabelaPreco;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class TabelaPrecoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ProtabelaPreco::class;
        $this->filters = ['id_filial', 'id_retaguarda'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_TABELA_PRECO_EXTERNA;
        $this->firstOrNew = ['id_filial', 'id_retaguarda'];
        $this->fields = [
            'id_filial',
            'id_retaguarda',
            'tab_desc',
            'tab_ini',
            'tab_fim',
            'gerar_verba'
        ];
        $this->modelComBarra = "\ProtabelaPreco";
        $this->tabela = "protabela_preco";
    }

    public function storeTabelaPreco(Request $request)
    {
        $where = ["id_filial" => $request->idFilial, "id_retaguarda" => $request->idRetaguarda];
        $this->destroyWhere($where);
        return $this->storePersonalizado($request);
    }

    public function moodificarTabelaPreco(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_TABELA_PRECO_EXTERNA);
            $where = ["id_filial" => $request->idFilial, "id_retaguarda" => $request->idRetaguarda];

            $tabelaPreco = ProtabelaPreco::firstOrNew($where);

            $tabelaPreco->id_filial = $request->idFilial;
            $tabelaPreco->id_retaguarda = $request->idRetaguarda;
            $tabelaPreco->tab_desc = $request->tabDesc;
            $tabelaPreco->tab_ini = $request->tabIni;
            $tabelaPreco->tab_fim = $request->tabFim;
            $tabelaPreco->gerar_verba = $request->gerarVerba;
            $tabelaPreco->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
