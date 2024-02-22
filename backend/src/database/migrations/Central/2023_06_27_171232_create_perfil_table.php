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
        Schema::create('perfil', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('descricao', 45);
            $table->unsignedInteger('fk_empresa')->nullable()->index('fk_perfil_empresa1_idx');
            $table->integer('fk_tipo_perfil')->index('fk_perfil_tipo_pefil1_idx');
            $table->integer('fk_tipo_empresa')->index('fk_perfil_tipo_empresa1_idx');
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();
            $table->dateTime('dt_modificado')->nullable();
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfil');
    }
};
