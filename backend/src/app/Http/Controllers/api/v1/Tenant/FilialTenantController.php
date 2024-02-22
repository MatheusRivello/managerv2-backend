<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Filial;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class FilialTenantController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }

    public function index()
    {
        try {
            return $this->service->verificarErro(Filial::paginate(20));
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function show($id)
    {
        try {
            return $this->service->verificarErro(Filial::where('id', $id)->get());
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_FILIAL_TENANT);

            $filial = Filial::firstOrNew(['emp_cgc' => $request->emp_cgc]);
            $filial->emp_cgc = $request->emp_cgc;
            $filial->emp_raz = $request->emp_raz;
            $filial->emp_fan = $request->emp_fan;
            $filial->emp_ativa = $request->emp_ativa;
            $filial->emp_uf = $request->emp_uf;
            $filial->emp_caminho_img = $request->emp_caminho_img;
            $filial->emp_url_img = $request->emp_url_img;
            $filial->emp_email = $request->emp_email;
            $filial->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
