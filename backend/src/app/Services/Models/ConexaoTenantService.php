<?php

namespace App\Services\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConexaoTenantService
{
    public static function definirConexaoTenant()
    {
        $user = Auth::guard()->user();
        if (isset($user)) {
            DB::setDefaultConnection("empresa" . $user->fk_empresa);
        }
    }

    public static function definirConexaoRelacionamento()
    {
        return ("empresa" . Auth::guard(Auth::getDefaultDriver())->user()->fk_empresa);
    }
    
}
