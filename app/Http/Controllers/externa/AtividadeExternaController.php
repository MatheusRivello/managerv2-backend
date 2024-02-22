<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\Atividade;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class AtividadeExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Atividade::class;
        $this->filters = ['id', 'id_filial', 'id_retaguarda','descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_ATIVIDADE_EXTERNA;
        $this->firstOrNew = ['id', 'descricao'];
        $this->fields = [
            'id_filial',
            'id_retaguarda',
            'descricao',
            'status'
        ];
    }

    public function modificarTabelaAtividade(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_ATIVIDADE_EXTERNA);
            $where = ["id_filial" => $request->idFilial, "id_retaguarda" => $request->idRetaguarda];

            $atividade = Atividade::firstOrNew($where);

            $atividade->id_filial = $request->idFilial;
            $atividade->id_retaguarda = $request->idRetaguarda;
            $atividade->descricao = $request->descricao;
            $atividade->status = $request->status;
            $atividade->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
