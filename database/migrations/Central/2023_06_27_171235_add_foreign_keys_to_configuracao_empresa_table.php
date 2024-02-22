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
        Schema::table('configuracao_empresa', function (Blueprint $table) {
            $table->foreign(['fk_empresa'], 'fk_configuracao_empresa_empresa1')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_tipo_configuracao_empresa'], 'fk_tipo_configuracao_empresa')->references(['id'])->on('tipo_configuracao_empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracao_empresa', function (Blueprint $table) {
            $table->dropForeign('fk_configuracao_empresa_empresa1');
            $table->dropForeign('fk_tipo_configuracao_empresa');
        });
    }
};
