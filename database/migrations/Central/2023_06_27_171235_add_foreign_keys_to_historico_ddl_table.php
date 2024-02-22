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
        Schema::table('historico_ddl', function (Blueprint $table) {
            $table->foreign(['fk_cabecalho_ddl'], 'fk_historico_ddl_cabecalho_ddl1')->references(['id'])->on('cabecalho_ddl')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_usuario'], 'fk_historico_ddl_usuario1')->references(['id'])->on('usuario')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_corpo_ddl'], 'fk_historico_ddl_corpo_ddl1')->references(['id'])->on('corpo_ddl')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historico_ddl', function (Blueprint $table) {
            $table->dropForeign('fk_historico_ddl_cabecalho_ddl1');
            $table->dropForeign('fk_historico_ddl_usuario1');
            $table->dropForeign('fk_historico_ddl_corpo_ddl1');
        });
    }
};
