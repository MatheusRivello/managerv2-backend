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
        Schema::create('corpo_requisicao_zip', function (Blueprint $table) {
            $table->string('fk_token_cabecalho_requisicao', 80);
            $table->string('metodo', 100);
            $table->boolean('acao')->comment('1=Limpar tabela, 2=Inativar registros');
            $table->integer('pacote_total');
            $table->dateTime('dt_inicio_envio_pacote')->nullable();
            $table->dateTime('dt_fim_envio_pacote')->nullable();
            $table->dateTime('dt_inicio_execucao_pacote')->nullable();
            $table->dateTime('dt_fim_execucao_pacote')->nullable();
            $table->text('mensagem')->nullable();
            $table->tinyInteger('status')->comment('1=Recebendo Pacote, 2=Pacotes Recebidos, 3=Executando no banco, 4=Fim execução no banco, 5=Erro');

            $table->primary(['fk_token_cabecalho_requisicao', 'metodo']);
            $table->unique(['fk_token_cabecalho_requisicao', 'metodo'], 'unicos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corpo_requisicao_zip');
    }
};
