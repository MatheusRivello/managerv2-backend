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
        Schema::create('aviso_empresa_usuario', function (Blueprint $table) {
            $table->integer('fk_aviso')->index('fk_aviso_empresa_usuario_aviso1_idx');
            $table->unsignedInteger('fk_empresa')->index('fk_aviso_empresa_usuario_empresa1_idx');
            $table->integer('fk_usuario')->nullable()->index('fk_aviso_empresa_usuario_usuario1_idx');
            $table->integer('qtd_visualizacao')->nullable()->default(0);

            $table->primary(['fk_aviso', 'fk_empresa']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aviso_empresa_usuario');
    }
};
