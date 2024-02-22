<?php

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\VendaPlano;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class VendaPlanoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = VendaPlano::class;
        $this->filters = ['id', 'id_filial', 'id_cliente', 'nfs_num', 'nfs_serie', 'nfs_doc', 'nfs_emissao', 'tipo_saida'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_VENDA_PLANO;
        $this->firstOrNew = ['id_filial', 'idCliente', 'nfsNum',];
        $this->tabela = 'venda_plano';
        $this->modelComBarra = '\VendaPlano';
        $this->fields = [
            'id_filial',
            'id_cliente',
            'nfs_num',
            'nfs_serie',
            'nfs_doc',
            'nfs_emissao',
            'tipo_saida'
        ];
    }

    public function storeVendaPlano(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_VENDA_PLANO);
            $registro = VendaPlano::firstornew(['id_filial' => $request->idFilial, 'id_cliente' => $request->idCliente, 'nfs_num' => $request->nfsNum]);
            $registro->id_filial = $request->idFilial;
            $registro->id_cliente = $request->idCliente;
            $registro->nfs_num = $request->nfsNum;
            $registro->nfs_serie = $request->nfsSerie;
            $registro->nfs_doc = $request->nfsDoc;
            $registro->nfs_emissao = $request->nfsEmissao;
            $registro->tipo_saida = $request->tipoSaida;
            $registro->save();

            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
