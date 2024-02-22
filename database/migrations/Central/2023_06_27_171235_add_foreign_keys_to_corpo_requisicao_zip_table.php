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
        Schema::table('corpo_requisicao_zip', function (Blueprint $table) {
            $table->foreign(['fk_token_cabecalho_requisicao'], 'fk_token_cabecalho')->references(['token'])->on('cabecalho_requisicao_zip')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corpo_requisicao_zip', function (Blueprint $table) {
            $table->dropForeign('fk_token_cabecalho');
        });
    }
};
