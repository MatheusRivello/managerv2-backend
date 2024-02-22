<?php

namespace App\Http\Controllers\api\v1\Config;

use App\Http\Controllers\Controller;
use App\Models\Central\GrupoRelatorio;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }

    public function index()
    {
        return $this->service->verificarErro(GrupoRelatorio::all());
    }

    public function show($id)
    {
        return $this->service->verificarErro(GrupoRelatorio::where('id', $id)->with("relatorios")->get());
    }

    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_GRUPO_RELATORIO, $request->id);

            $grupo = GrupoRelatorio::where('id', $request->id)->firstOrNew();
            $grupo->descricao = $request->descricao;
            $grupo->id_empresa = $request->empresa;
            $grupo->status = $request->status;
            $grupo->save();

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

            $registro = GrupoRelatorio::find($id);

            if (!isset($registro)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }else if ($registro->getRelatoriosCount() > 0){
                throw new Exception(EXISTE_RELACIONAMENTOS, 406);
            }

            if ($registro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
