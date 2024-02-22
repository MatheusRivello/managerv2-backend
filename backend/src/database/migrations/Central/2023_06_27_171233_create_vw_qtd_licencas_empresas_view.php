<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW `vw_qtd_licencas_empresas` AS select `central_afv`.`empresa`.`id` AS `id`,`central_afv`.`empresa`.`razao_social` AS `empresa`,`central_afv`.`empresa`.`qtd_licenca` AS `qtd_contratado`,(select count(`central_afv`.`dispositivo`.`id`) from `central_afv`.`dispositivo` where ((`central_afv`.`dispositivo`.`fk_empresa` = `central_afv`.`empresa`.`id`) and (`central_afv`.`dispositivo`.`status` = 1))) AS `qtd_em_uso` from `central_afv`.`empresa` where ((`central_afv`.`empresa`.`id` <> 13) and (`central_afv`.`empresa`.`status` = 1))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS `vw_qtd_licencas_empresas`");
    }
};
