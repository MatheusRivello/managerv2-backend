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
        Schema::create('cabecalho_requisicao_zip', function (Blueprint $table) {
            $table->string('token', 100)->primary();
            $table->unsignedInteger('fk_empresa')->index('fk_cabecalho_requisicao_zip_empresa1_idx');
            $table->integer('fk_usuario')->nullable()->index('fk_cabecalho_requisicao_zip_usuario1_idx');
            $table->boolean('tipo_requisicao')->nullable()->comment('1=critica, 2=online, 3=programa');
            $table->integer('qtd_metodo');
            $table->string('metodo', 200)->comment('Ex: (12,21,30,31) -> Ids do tipo_configuracao_empresa');
            $table->string('recebendo_zip', 200)->nullable()->comment('Exibe quais são os métodos que estão sendo enviando no momento');
            $table->string('metodo_em_execucao', 200)->nullable();
            $table->text('caminho')->comment('Pasta principal da requisição');
            $table->dateTime('dt_inicio_envio_pacotes');
            $table->dateTime('dt_fim_envio_pacotes')->nullable();
            $table->dateTime('dt_inicio_execucao_pacotes')->nullable();
            $table->dateTime('dt_fim_execucao_pacotes')->nullable();
            $table->boolean('status')->default(true)->comment('1=Enviando pacotes, 2=Executando pacotes, 3=Fim de execução dos pacotes, 4=Erro crítico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cabecalho_requisicao_zip');
    }
};
