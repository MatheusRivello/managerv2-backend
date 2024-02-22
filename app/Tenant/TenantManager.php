<?php
declare(strict_types=1);

namespace App\Tenant;

use App\Models\Central\Empresa;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

class TenantManager
{
    private $tenant;

    /**
     * @return Empresa
     */
    public function getTenant(): ?Empresa
    {
        return $this->tenant;
    }

    /**
     * @param Empresa $tenant
     */
    public function setTenant(?Empresa $tenant): void
    {
        $this->tenant = $tenant;
        $this->makeTenantConnection();
    }

    private function makeTenantConnection()
    {
        $clone = config('database.connections.system');
        $clone['database'] = $this->tenant->database;
        Config::set('database.connections.tenant', $clone);
        DB::reconnect('tenant');
    }

    public function loadConnections()
    {
        if (Schema::hasTable((new Empresa())->getTable())) {
            $empresas = Empresa::all();
            foreach ($empresas as $empresa) {
                $clone = config('database.connections.tenant');
                $clone['host'] = "{$empresa->bd_host}";
                $clone['port'] = "{$empresa->bd_porta}";
                $clone['username'] = "{$empresa->bd_usuario}";
                $clone['password'] = "{$empresa->bd_senha}";
                $clone['database'] = "{$empresa->bd_nome}";
                $clone['prefixoOnController'] = "empresa{$empresa->id}";
                Config::set("database.connections.empresa{$empresa->id}", $clone); //empresa1
                DB::purge("empresa{$empresa->id}");
            }
        }
    }

    public function isTenantRequest(){
        return !Request::is('system/*') && Request::route('prefix');
    }
}