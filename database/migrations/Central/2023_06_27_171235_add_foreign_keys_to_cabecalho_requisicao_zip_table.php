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
        Schema::table('cabecalho_requisicao_zip', function (Blueprint $table) {
            $table->foreign(['fk_empresa'], 'fk_cabecalho_requisicao_zip_empresa1')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_usuario'], 'fk_cabecalho_requisicao_zip_usuario1')->references(['id'])->on('usuario')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cabecalho_requisicao_zip', function (Blueprint $table) {
            $table->dropForeign('fk_cabecalho_requisicao_zip_empresa1');
            $table->dropForeign('fk_cabecalho_requisicao_zip_usuario1');
        });
    }
};
