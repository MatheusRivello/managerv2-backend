<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\VersaoApp;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class VersaoAppController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }

    public function index()
    {
        return $this->service->verificarErro(VersaoApp::all());
    }

    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_VERSAO_CENTRAL);

            $versao = new VersaoApp();

            $versao->codigo_versao = VersaoApp::get()->max("codigo_versao") + 1;
            $versao->versao = $request->versao;
            $versao->obrigatorio = $request->obrigatorio;
            $versao->observacao = $request->observacao;
            $versao->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function show($codigo_versao)
    {
        return $this->service->verificarErro(VersaoApp::where('codigo_versao', $codigo_versao)->get());
    }

    public function update(Request $request, $codigo_versao)
    {
        try {
            $this->service->verificarCamposRequest($request, $codigo_versao);

            $versao = VersaoApp::find($codigo_versao);

            if (!$versao) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 409);
            }

            $versao->update($request->all());

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroy($codigo_versao)
    {
        try {
            if (empty($codigo_versao)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $versao = VersaoApp::find($codigo_versao);
            
            if (!isset($versao)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($versao->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
