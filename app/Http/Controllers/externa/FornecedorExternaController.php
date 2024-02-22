<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Fornecedor;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class FornecedorExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Fornecedor::class;
        $this->filters = ['id', 'id_filial', 'id_retaguarda', 'nome_fantasia', 'razao_social'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_FORNECEDOR_EXTERNA;
        $this->firstOrNew = ['id_filial', 'id_retaguarda', 'razao_social'];
        $this->fields = [
            'id_filial',
            'id_retaguarda',
            'nome_fantasia',
            'razao_social',
            'status'
        ];
    }

    public function storeFornecedor(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_FORNECEDOR_EXTERNA);

            $registro = Fornecedor::firstornew(['id_filial' => $request->idFilial, 'id_retaguarda' => $request->idRetaguarda, 'razao_social' => $request->razaoSocial]);
            $registro->id_filial = $request->idFilial;
            $registro->id_retaguarda = $request->idRetaguarda;
            $registro->nome_fantasia = $request->nomeFantasia;
            $registro->razao_social = $request->razaoSocial;
            $registro->status = $request->status;
            $registro->save();

            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 406);
        }
    }
}
