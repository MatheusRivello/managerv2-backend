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
        Schema::table('grupo_relatorio', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'grupo_relatorio_ibfk_1')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grupo_relatorio', function (Blueprint $table) {
            $table->dropForeign('grupo_relatorio_ibfk_1');
        });
    }
};
