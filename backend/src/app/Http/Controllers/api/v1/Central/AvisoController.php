<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\Aviso;
use App\Models\Central\AvisoEmpresaUsuario;
use Illuminate\Http\Request;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

class AvisoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }

    public function index()
    {
        return Aviso::all();
    }

    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_AVISO_CENTRAL);

            $aviso = new Aviso();
            $aviso->fk_usuario = $this->service->usuarioLogado()->id;
            $aviso->titulo = $request->titulo;
            $aviso->descricao = $request->descricao;
            $aviso->caminho_imagem = $request->caminho_imagems;
            $aviso->url_imagem = $request->url_imagem;
            $aviso->url_imagem_thumb = $request->url_imagem_thumb;
            $aviso->status = $request->status;
            $aviso->dt_inicio = $request->dt_inicio;
            $aviso->dt_fim = $request->dt_fim;
            $aviso->dt_cadastro = $request->dt_cadastro;
            $aviso->dt_modificado = $request->dt_modificado;
            $aviso->obrigatorio = $request->obrigatorio;
            $aviso->exibir_titulo = $request->exibir_titulo;

            if ($aviso->save() && isset($request->empresas)) {
                foreach ($request->empresas as $empresa) {
                    $avisoEmpresa = new AvisoEmpresaUsuario;
                    $avisoEmpresa->fk_aviso = $aviso->id;
                    $avisoEmpresa->fk_empresa = $empresa;
                    $avisoEmpresa->save();
                }
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $aviso = Aviso::where('id', $id)->get();

        return response()->json([$this->service->verificarErro($aviso)], isset($aviso) ? 200 : 404);
    }

    public function update(Request $request, $id)
    {
        try {
            $aviso = Aviso::find($id);
            if (!$aviso) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 409);
            }

            $this->service->verificarCamposRequest($request, RULE_AVISO_CENTRAL, $id);

            if ($aviso->update($request->all()) && isset($request->empresas)) {
                AvisoEmpresaUsuario::where("fk_aviso", $id)->delete();

                foreach ($request->empresas as $empresa) {
                    $avisoEmpresa = new AvisoEmpresaUsuario;
                    $avisoEmpresa->fk_aviso = $id;
                    $avisoEmpresa->fk_empresa = $empresa;
                    $avisoEmpresa->save();
                }
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function contadorView($id)
    {
        try {
            $avisoEmpresa = AvisoEmpresaUsuario::where([['fk_aviso', $id], ['fk_empresa', $this->service->usuarioLogado()->fk_empresa]])->update(['qtd_visualizacao' => DB::raw('qtd_visualizacao+1')]);
            
            if($avisoEmpresa < 1){
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 404);
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $perfil = Aviso::find($id);

            if (!isset($perfil)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($perfil->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
