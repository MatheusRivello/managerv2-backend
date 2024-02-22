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
        Schema::table('relatorios', function (Blueprint $table) {
            $table->foreign(['id_grupo'], 'relatorios_ibfk_1')->references(['id'])->on('grupo_relatorio')->onUpdate('CASCADE');
            $table->foreign(['tipo_grafico'], 'relatorios_ibfk_2')->references(['id'])->on('tipo_grafico')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relatorios', function (Blueprint $table) {
            $table->dropForeign('relatorios_ibfk_1');
            $table->dropForeign('relatorios_ibfk_2');
        });
    }
};
