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
        Schema::create('log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('tipo')->nullable()->comment('LOG_DELETAR = 3
LOG_ATUALIZACAO = 2
LOG_NOVO = 1

LOG_PEDIDO_OK = 20
LOG_PEDIDO_FALHA = 21
LOG_LOGIN_VALIDO = 10
LOG_LOGIN_INVALIDO = 11
LOG_LOGIN_BLOQUEADO = 12');
            $table->string('tipo_acesso', 15)->nullable()->comment('Informará se o acesso foi da Sig2000 ou de uma empresa');
            $table->integer('fk_empresa')->nullable()->index();
            $table->integer('fk_usuario')->nullable()->index('fk_usuario_log');
            $table->integer('id_cliente')->nullable();
            $table->string('ip', 35)->nullable();
            $table->dateTime('dt_cadastro')->useCurrent()->index('log_index_dt_cadastro');
            $table->string('tabela', 100)->nullable()->index();
            $table->text('mensagem')->nullable();
            $table->binary('conteudo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log');
    }
};
