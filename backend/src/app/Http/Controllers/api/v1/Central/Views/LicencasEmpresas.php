<?php

namespace App\Http\Controllers\api\v1\Central\Views;

use App\Http\Controllers\Controller;
use App\Models\Central\VwQtdLicencasEmpresa;
use Illuminate\Http\Request;

class LicencasEmpresas extends Controller
{
    public function index()
    {
        return VwQtdLicencasEmpresa::all();
    }

    public function show($id)
    {
        $licenca = VwQtdLicencasEmpresa::find($id);

        $messageError = [
            "message" => "Registro não encontrado"
        ];

        $verificaMessage = isset($licenca) ? $licenca : $messageError;

        return response()->json([$verificaMessage], isset($licenca) ? 200 : 404);
    }
}
