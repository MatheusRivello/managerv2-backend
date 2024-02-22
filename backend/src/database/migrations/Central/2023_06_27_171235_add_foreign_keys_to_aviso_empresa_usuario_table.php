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
        Schema::table('aviso_empresa_usuario', function (Blueprint $table) {
            $table->foreign(['fk_aviso'], 'fk_aviso_empresa_usuario_aviso1')->references(['id'])->on('aviso')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_usuario'], 'fk_aviso_empresa_usuario_usuario1')->references(['id'])->on('usuario')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_empresa'], 'fk_aviso_empresa_usuario_empresa1')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aviso_empresa_usuario', function (Blueprint $table) {
            $table->dropForeign('fk_aviso_empresa_usuario_aviso1');
            $table->dropForeign('fk_aviso_empresa_usuario_usuario1');
            $table->dropForeign('fk_aviso_empresa_usuario_empresa1');
        });
    }
};
