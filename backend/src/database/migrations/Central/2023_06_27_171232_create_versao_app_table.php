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
        Schema::create('versao_app', function (Blueprint $table) {
            $table->smallInteger('codigo_versao')->primary();
            $table->string('versao', 45)->comment('Nome da versão');
            $table->boolean('obrigatorio')->comment('0=Não obrigatório, 1=Obrigatório');
            $table->text('observacao')->nullable()->comment('Observações sobre a atualização');
            $table->dateTime('dt_cadastro')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('versao_app');
    }
};
