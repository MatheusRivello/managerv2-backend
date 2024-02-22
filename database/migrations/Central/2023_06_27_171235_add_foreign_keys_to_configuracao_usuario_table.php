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
        Schema::table('configuracao_usuario', function (Blueprint $table) {
            $table->foreign(['fk_tipo_configuracao_usuario'], 'fk_tipo_configuracao_usuario')->references(['id'])->on('tipo_configuracao_usuario')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_usuario'], 'fk_usuario_conf')->references(['id'])->on('usuario')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracao_usuario', function (Blueprint $table) {
            $table->dropForeign('fk_tipo_configuracao_usuario');
            $table->dropForeign('fk_usuario_conf');
        });
    }
};
