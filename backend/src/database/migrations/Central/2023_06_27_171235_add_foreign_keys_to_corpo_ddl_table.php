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
        Schema::table('corpo_ddl', function (Blueprint $table) {
            $table->foreign(['id_cabecalho_ddl'], 'fk_corpo_dll_cabecalho_dll1')->references(['id'])->on('cabecalho_ddl')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_empresa'], 'fk_corpo_dll_empresa1')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corpo_ddl', function (Blueprint $table) {
            $table->dropForeign('fk_corpo_dll_cabecalho_dll1');
            $table->dropForeign('fk_corpo_dll_empresa1');
        });
    }
};
