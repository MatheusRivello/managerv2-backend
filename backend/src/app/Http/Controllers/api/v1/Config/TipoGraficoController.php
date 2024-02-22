<?php

namespace App\Http\Controllers\api\v1\Config;

use App\Http\Controllers\Controller;
use App\Models\Central\TipoGrafico;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class TipoGraficoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }

    public function index()
    {
        return $this->service->verificarErro(TipoGrafico::all());
    }

    public function show($id)
    {
        return $this->service->verificarErro(TipoGrafico::where('id', $id)->with('relatorios')->get());
    }

    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_TIPO_GRAFICO, $request->id);
            $tipoGrafico = TipoGrafico::where('id', $request->id)->firstOrNew();
            $tipoGrafico->descricao = $request->descricao;
            $tipoGrafico->status = $request->status;
            $tipoGrafico->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $registro = TipoGrafico::find($id);

            if (!isset($registro)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($registro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
