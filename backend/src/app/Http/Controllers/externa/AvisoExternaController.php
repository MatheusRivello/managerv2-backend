<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Aviso;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class AvisoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Aviso::class;
        $this->filters = ['id_filial', 'descricao'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_AVISO_EXTERNA;
        $this->firstOrNew = ['id_filial', 'descricao', 'tipo'];
        $this->fields = [
            'id_filial',
            'descricao',
            'dt_inicio',
            'dt_fim',
            'tipo'
        ];
    }

    public function showPersonalizado(Request $request)
    {
        try {
            $resultado = Aviso::where(function ($query) use ($request) {
                if (!is_null($request->idFilial)) $query->whereIn('id_filial', $request->id);
                if (!is_null($request->descricao)) $query->where('descricao', $request->descricao);
                if (!is_null($request->tipo)) $query->whereIn('descricao', $request->tipo);
                if (!is_null($request->dtInicio && $request->dtFim)) $query->whereIn('dt_inicio', $request->dtInicio, '<=', 'dt_fim', $request->dtFim);
            })
                ->paginate(20);

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['erro' => true, 'message' => $ex->getMessage()]);
        }
    }
}
