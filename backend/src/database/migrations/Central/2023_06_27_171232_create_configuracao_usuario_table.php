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
        Schema::create('configuracao_usuario', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('fk_usuario')->index('fk_usuario_conf');
            $table->integer('fk_tipo_configuracao_usuario')->index('fk_tipo_configuracao_usuario');
            $table->text('valor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracao_usuario');
    }
};
