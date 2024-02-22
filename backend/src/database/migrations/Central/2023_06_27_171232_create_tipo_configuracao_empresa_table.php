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
        Schema::create('tipo_configuracao_empresa', function (Blueprint $table) {
            $table->integer('id')->comment('O id tem que ser o mesmo que esta no sistema local de sincronização');
            $table->integer('fk_empresa');
            $table->boolean('tipo')->comment('0=loca, 1=AFV');
            $table->text('label');

            $table->primary(['id', 'fk_empresa', 'tipo']);
            $table->index(['id', 'fk_empresa', 'tipo'], 'ids_unicos_tipo_conf_empresa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_configuracao_empresa');
    }
};
