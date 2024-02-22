<?php

namespace App\Console\Commands;

use App\Models\Central\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create {--ids= : Ids of tenants to create structure}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new tenants';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ids = explode(",", $this->option('ids'));//1,2,3
        $empresas = Empresa::whereIn('id', $ids)->get();
        foreach ($empresas as $empresa) {
            DB::statement("CREATE DATABASE {$empresa->bd_nome};");

            $this->call('migrate', [
                '--database' => "empresa". $empresa->id, //conexao empresa178
                '--path' => 'database/migrations/tenant',
                // '--seed'
            ]);
        }
        if(!$empresas->count()){
            $this->error('Ids of tenant not found in table.');
        }
    }
}