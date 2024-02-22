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
        Schema::create('cerca_eletronica', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fk_empresa')->index('fk_log_token_gerado_empresa1_idx');
            $table->integer('fk_usuario')->index('fk_log_token_gerado_usuario1_idx');
            $table->integer('fk_motivo_cerca_eletronica')->index('fk_cerca_eletronica_motivo_cerca_eletronica1_idx');
            $table->string('dt_cadastro', 16);
            $table->integer('id_vendedor')->nullable();
            $table->string('token', 20);
            $table->string('mac', 20)->nullable();
            $table->string('tipo_gerado', 4);
            $table->string('observacao', 100)->nullable();

            $table->unique(['fk_empresa', 'dt_cadastro', 'id_vendedor', 'token'], 'id_unicos_cerca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cerca_eletronica');
    }
};
