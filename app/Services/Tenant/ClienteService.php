<?php

namespace App\Services\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Cliente;
use App\Services\BaseService;
use Exception;

class ClienteService
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }

    public function getClientes(Request $request)
    {

        try {
            if ($request->nome != '') {
                if ($request->resumido == 1) {
                    $resultado = Cliente::select(
                        "id",
                        "id_filial",
                        "razao_social",
                        "nome_fantasia"
                    )
                        ->where('nome_fantasia', 'like', "%$request->nome%")
                        ->orWhere('razao_social', 'like', "%$request->nome%")
                        ->where('id_filial', $request->idFilial)
                        ->get();
                    return $this->service->verificarErro($resultado);
                } else {
                    $resultado = Cliente::select()
                        ->where('nome_fantasia', 'like', "%$request->nome%")
                        ->orWhere('razao_social', 'like', "%$request->nome%")
                        ->where('id_filial', $request->idFilial)
                        ->get();

                    return $this->service->verificarErro($resultado);
                }
            }
            if ($request->nome == '' && $request->idFilial != '') {
                if ($request->resumido == 1) {
                    $resultado = Cliente::select(
                        "id",
                        "id_filial",
                        "razao_social",
                        "nome_fantasia"
                    )
                        ->where('id_filial', $request->idFilial)
                        ->get();

                    return $this->service->verificarErro($resultado);
                } else {
                    $resultado = Cliente::select()
                        ->where('id_filial', $request->idFilial)
                        ->get();

                    return $this->service->verificarErro($resultado);
                }
            }
            if ($request->idFilial == '' && $request->nome == '' && $request->resumido == 1) {
                $resultado = Cliente::select(
                    "id",
                    "id_filial",
                    "razao_social",
                    "nome_fantasia"
                )
                    ->get();
                return $this->service->verificarErro($resultado);
            } else {
                $resultado = Cliente::select()
                    ->paginate(20);
            }

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
