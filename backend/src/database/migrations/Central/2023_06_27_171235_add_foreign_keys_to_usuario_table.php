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
        Schema::table('usuario', function (Blueprint $table) {
            $table->foreign(['fk_empresa'], 'fk_empresa_usu')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_tipo_empresa'], 'fk_usuario_tipo_empresa1')->references(['id'])->on('tipo_empresa')->onUpdate('CASCADE');
            $table->foreign(['fk_perfil'], 'fk_usuario_perfil1')->references(['id'])->on('perfil')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropForeign('fk_empresa_usu');
            $table->dropForeign('fk_usuario_tipo_empresa1');
            $table->dropForeign('fk_usuario_perfil1');
        });
    }
};
