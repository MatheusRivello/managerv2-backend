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
        Schema::create('tipo_perfil', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('descricao', 45)->comment('0=Vendedor, 1=Supervisor, 2=Gerente, 3=Comun');
            $table->integer('fk_tipo_empresa')->index('fk_tipo_pefil_tipo_empresa1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_perfil');
    }
};
