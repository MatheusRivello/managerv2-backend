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
        Schema::create('detalhe_requisicao_zip', function (Blueprint $table) {
            $table->string('fk_token_corpo_requisicao', 80)->index('fk_token_corpo_requisicao_idx');
            $table->integer('pacote_atual');
            $table->dateTime('dt_inicio');
            $table->dateTime('dt_fim')->nullable();
            $table->dateTime('dt_inicio_execucao')->nullable();
            $table->dateTime('dt_fim_execucao')->nullable();
            $table->text('mensagem')->nullable();
            $table->boolean('status')->comment('1=Recebendo Pacote, 2=Pacotes Recebidos, 3=Executando no banco, 4=Fim execução no banco, 5=Erro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalhe_requisicao_zip');
    }
};
