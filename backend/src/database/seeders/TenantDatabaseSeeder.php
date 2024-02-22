<?php

use App\Models\Central\Empresa;
use App\Tenant\TenantFacade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    { //db:seed --database=company1
        $connection = DB::getDefaultConnection();
        $empresa = Empresa::where('prefix', $connection)->first();
        TenantFacade::setTenant($empresa);
        // $this->call(UserTenantsTableSeeder::class);
    }
}