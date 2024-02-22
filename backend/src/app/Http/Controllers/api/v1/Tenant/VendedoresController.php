<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Vendedor;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendedoresController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }

    public function getVendedors(Request $request)
    {
        try {

            $resultado = Vendedor::select(
                "id",
                DB::raw("case(tipo) when 0 then 'Vendedor' when 1 then 'Supervisor' when 2 then 'Gerente' end as cargo"),
                "nome",
                "status",
                "usuario",
                "supervisor",
                "gerente",
                "sequencia_pedido",
                "saldoVerba"
            )
                ->where(function ($query) use ($request) {
                    if (!is_null($request->id)) $query->where("id", $request->id);
                    if (!is_null($request->somenteSupervisor) && $request->somenteSupervisor === true) $query->where("tipo", 1);
                    if (!is_null($request->somenteGerente) && $request->somenteGerente === true) $query->where("tipo", 2);
                    if (!is_null($request->somenteVendedor) && $request->somenteVendedor === true) $query->where("tipo", 0);
                    if (!is_null($request->gerente)) $query->where("gerente", $request->gerente);
                    if (!is_null($request->supervisor)) $query->where("supervisor", $request->supervisor);
                })
                ->with(["supervisor:id,nome", "gerente:id,nome"])
                ->get();

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
