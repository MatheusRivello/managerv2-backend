<?php

namespace App\Services\Migrate;

use App\Models\Central\Empresa;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateService
{
    public function tenant($tenant)
    {
        $path = "database/migrations/Tenant";
        
        $command = "migrate --database=tenant --path=$path";

        $empresa = Empresa::find($tenant);
        
        $config = $this->getConnectionConfigById($empresa);

        config(['database.connections.tenant' => $config]);

        DB::statement("CREATE DATABASE $empresa->bd_nome;");

        Artisan::call($command);

        return Artisan::output();
    }

    public function getConnectionConfigById($empresa)
    {
        $config = config('database.connections.tenant');
        $config['host'] = "{$empresa->host}";
        $config['port'] = "{$empresa->bd_porta}";
        $config['username'] = "{$empresa->bd_usuario}";
        $config['password'] = "{$empresa->bd_senha}";
        $config['database'] = "{$empresa->bd_nome}";
        $config['prefixoOnController'] = "empresa{$empresa->id}";

        return $config;
    }
}