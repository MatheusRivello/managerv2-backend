<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao_dispositivo', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fk_empresa')->index('fk_empresa');
            $table->unsignedInteger('fk_dispositivo')->index('fk_dispositivo_conf');
            $table->unsignedInteger('fk_tipo_configuracao')->index('fk_tipo_configuracao_conf_idx');
            $table->text('valor');

            $table->unique(['fk_empresa', 'fk_dispositivo', 'fk_tipo_configuracao'], 'ids_unicos_configuracao_d');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracao_dispositivo');
    }
};
