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
        Schema::table('cerca_eletronica', function (Blueprint $table) {
            $table->foreign(['fk_motivo_cerca_eletronica'], 'fk_cerca_eletronica_motivo_cerca_eletronica1')->references(['id'])->on('motivo_cerca_eletronica')->onUpdate('CASCADE');
            $table->foreign(['fk_usuario'], 'fk_log_token_gerado_usuario1')->references(['id'])->on('usuario')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_empresa'], 'fk_log_token_gerado_empresa1')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cerca_eletronica', function (Blueprint $table) {
            $table->dropForeign('fk_cerca_eletronica_motivo_cerca_eletronica1');
            $table->dropForeign('fk_log_token_gerado_usuario1');
            $table->dropForeign('fk_log_token_gerado_empresa1');
        });
    }
};
