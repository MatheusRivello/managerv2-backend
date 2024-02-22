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
        Schema::table('configuracao_dispositivo', function (Blueprint $table) {
            $table->foreign(['fk_dispositivo'], 'fk_dispositivo_conf')->references(['id'])->on('dispositivo')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_tipo_configuracao'], 'fk_tipo_configuracao_conf')->references(['id'])->on('tipo_configuracao')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_empresa'], 'fk_empresa_conf')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracao_dispositivo', function (Blueprint $table) {
            $table->dropForeign('fk_dispositivo_conf');
            $table->dropForeign('fk_tipo_configuracao_conf');
            $table->dropForeign('fk_empresa_conf');
        });
    }
};
