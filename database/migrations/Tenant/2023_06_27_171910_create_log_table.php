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
        Schema::connection('tenant')->create('log', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 50)->comment('LOG_DELETAR = 3
LOG_ATUALIZACAO = 2
LOG_NOVO = 1

LOG_PEDIDO_OK = 20
LOG_PEDIDO_FALHA = 21
LOG_LOGIN_VALIDO = 10
LOG_LOGIN_INVALIDO = 11
LOG_LOGIN_BLOQUEADO = 12');
            $table->integer('id_empresa')->nullable();
            $table->string('mac', 12)->nullable();
            $table->integer('id_cliente')->nullable();
            $table->integer('id_filial')->nullable()->index('fk_log_filial1_idx');
            $table->string('tabela', 100)->nullable();
            $table->binary('conteudo')->nullable();
            $table->text('mensagem')->nullable()->comment('padroes: inserido, falha_inserir, atualizado, falha_atualizar, deletado, falha_deletar');
            $table->string('ip', 35)->nullable();
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
        Schema::connection('tenant')->dropIfExists('log');
    }
};
