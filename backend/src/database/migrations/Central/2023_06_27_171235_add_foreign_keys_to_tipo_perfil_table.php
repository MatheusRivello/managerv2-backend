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
        Schema::table('tipo_perfil', function (Blueprint $table) {
            $table->foreign(['fk_tipo_empresa'], 'fk_tipo_pefil_tipo_empresa1')->references(['id'])->on('tipo_empresa')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipo_perfil', function (Blueprint $table) {
            $table->dropForeign('fk_tipo_pefil_tipo_empresa1');
        });
    }
};
