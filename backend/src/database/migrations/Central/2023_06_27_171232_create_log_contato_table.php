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
        Schema::create('log_contato', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('fk_empresa');
            $table->integer('id_cliente')->nullable();
            $table->string('nome', 100);
            $table->string('email', 100);
            $table->string('telefone', 50)->nullable();
            $table->text('mensagem');
            $table->string('ip', 20);
            $table->dateTime('dt_cadastro')->useCurrent();
            $table->dateTime('dt_enviado')->nullable();
            $table->boolean('status')->default(false)->comment('0=Não Enviado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_contato');
    }
};
