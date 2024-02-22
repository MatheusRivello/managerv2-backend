<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\Api;
use App\Models\Central\Menu;
use Illuminate\Http\Request;
use App\Services\BaseService;
use Exception;

class AcessosController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }

    public function indexAPI()
    {
        return $this->service->verificarErro(API::all());
    }

    public function showAPI($id)
    {
        return $this->service->verificarErro(API::where('id', $id)->get());
    }

    public function storeUpdateAPI(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_ACESSO_API, $request->id);

            $api = API::firstOrNew(["id" => $request->id]);

            $api->url = $request->url;
            $api->prefix = $request->prefix;
            $api->descricao = $request->descricao;
            $api->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroyAPI($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $api = API::find($id);

            if (!isset($api)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($api->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
    
    public function indexMENU()
    {
        return $this->service->verificarErro(MENU::all());
    }

    public function showMENU($id)
    {
        return $this->service->verificarErro(MENU::where('id', $id)->get());
    }

    public function showArvoreMENU()
    {
        return $this->service->verificarErro(
            MENU::where('fk_menu', null)
                ->with('menus:id,fk_menu,descricao')
                ->select('menu.id','menu.descricao')
                ->get()
        );
    }

    public function storeUpdateMENU(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_ACESSO_MENU, $request->id);

            $menu = MENU::firstOrNew(["id" => $request->id]);

            $menu->fk_tipo_empresa = $request->fk_tipo_empresa;
            $menu->fk_menu = $request->fk_menu;
            $menu->fk_tipo_permissao = $request->fk_tipo_permissao;
            $menu->classe = $request->classe;
            $menu->descricao = $request->descricao;
            $menu->url = $request->url;
            $menu->personalizado = $request->personalizado;
            $menu->extra = $request->extra;
            $menu->ordem = $request->ordem;
            $menu->exibir_cabecalho = $request->exibir_cabecalho;
            $menu->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroyMENU($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $menu = MENU::find($id);

            if (!isset($menu)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($menu->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
