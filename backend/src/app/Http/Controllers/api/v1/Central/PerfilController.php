<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\Perfil;
use App\Models\Central\PerfilMenu;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }

    public function index()
    {
        return $this->service->verificarErro(Perfil::all());
    }


    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_PERFIL_CENTRAL);

            $perfil = new Perfil();
            $perfil->descricao = $request->descricao;
            $perfil->fk_empresa = $this->service->usuarioLogado()->fk_empresa;
            $perfil->fk_tipo_perfil = $request->fk_tipo_perfil;
            $perfil->fk_tipo_empresa = $request->fk_tipo_empresa;
            $perfil->status = $request->status;
            if ($perfil->save()) {
                foreach ($request->menus as $menu) {
                    $perfilMenu = new PerfilMenu;
                    $perfilMenu->fk_perfil = $perfil->id;
                    $perfilMenu->fk_menu = $menu;
                    $perfilMenu->save();
                }
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $perfil = Perfil::where('id', $id)->get();

        return response()->json([$this->service->verificarErro($perfil)], isset($perfil) ? 200 : 404);
    }

    public function showEmpresa($idEmpresa)
    {
        $perfil = Perfil::where("fk_empresa", $idEmpresa)->get();

        return response()->json([$this->service->verificarErro($perfil)], isset($perfil) ? 200 : 404);
    }

    public function showMenus($id)
    {
        $perfil = Perfil::where('id', $id)->with("menus")->get();

        return response()->json([$this->service->verificarErro($perfil)], isset($perfil) ? 200 : 404);
    }

    public function showApis()
    {
        $perfil = Perfil::where('id', $this->service->usuarioLogado()->fk_perfil)->with("url_api")->get();

        return response()->json([$this->service->verificarErro($perfil)], isset($perfil) ? 200 : 404);
    }
    public function showUsersProfiles($perfil)
    {
        $perfil = Perfil::where('perfil.fk_empresa', $this->service->usuarioLogado()->fk_empresa)->where('usuario.fk_perfil',$perfil)->join("usuario",'perfil.id','=','usuario.fk_perfil')->get();

        return response()->json([$this->service->verificarErro($perfil)], isset($perfil) ? 200 : 404);
    }

    public function update(Request $request, $id)
    {
        try {
            $perfil = Perfil::find($id);
            if (!$perfil) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 409);
            }

            $this->service->verificarCamposRequest($request, RULE_PERFIL_CENTRAL, $id);

            if ($perfil->update($request->all()) && isset($request->menus)) {
                PerfilMenu::where("fk_perfil", $id)->delete();

                foreach ($request->menus as $menu) {
                    $perfilMenu = new PerfilMenu;
                    $perfilMenu->fk_perfil = $perfil->id;
                    $perfilMenu->fk_menu = $menu;
                    $perfilMenu->save();
                }
            }

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

            $perfil = Perfil::find($id);

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
