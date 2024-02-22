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
        Schema::table('perfil', function (Blueprint $table) {
            $table->foreign(['fk_empresa'], 'fk_perfil_empresa1')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_tipo_perfil'], 'fk_perfil_tipo_pefil1')->references(['id'])->on('tipo_perfil')->onUpdate('CASCADE');
            $table->foreign(['fk_tipo_empresa'], 'fk_perfil_tipo_empresa1')->references(['id'])->on('tipo_empresa')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perfil', function (Blueprint $table) {
            $table->dropForeign('fk_perfil_empresa1');
            $table->dropForeign('fk_perfil_tipo_pefil1');
            $table->dropForeign('fk_perfil_tipo_empresa1');
        });
    }
};
