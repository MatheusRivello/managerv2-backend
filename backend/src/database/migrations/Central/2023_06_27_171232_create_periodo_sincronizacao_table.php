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
        Schema::create('periodo_sincronizacao', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('fk_empresa')->index('fk_periodo_sincronizacao_empresa1_idx');
            $table->integer('fk_usuario')->nullable();
            $table->string('dia', 150)->default('Seg,Ter,Qua,Qui,Sex,Sab,Dom')->comment('Ex: segunda-feira,terca-feira,quarta-feira,quinta-feira,sexta-feira,sabado,domingo');
            $table->text('hora')->nullable()->comment('Ex: 13:00,17:00,20:00');
            $table->integer('periodo')->nullable()->default(1)->comment('periodo para a sincronização em minutos, 
ex: 1');
            $table->integer('registro_lote')->nullable()->default(1);
            $table->integer('qtd_dias_nota_fiscal')->nullable()->default(5)->comment('Quantidade de notas que irão para nuvem');
            $table->integer('qtd_dias_nota_fiscal_app')->nullable()->default(30)->comment('Quantidade de notas que irão para o aplicativo AFV');
            $table->boolean('restricao_produto')->nullable()->default(false)->comment('0= Enviar todos os produtos par ao vendedor
1= Enviar apenas os produtos relacionado ao vendedor');
            $table->boolean('restricao_protabela_preco')->nullable()->default(false);
            $table->boolean('restricao_vendedor_cliente')->nullable()->default(false);
            $table->boolean('restricao_supervisor_cliente')->nullable()->default(false);
            $table->dateTime('dt_cadastro_online')->nullable()->comment('Quando o usuário cadastrar a integração online(Emergencial) no painel para o sistema local receber');
            $table->dateTime('dt_execucao_online')->nullable()->comment('Quando o processo entrar em execução no sistema local de integração');
            $table->dateTime('dt_execucao_online_fim')->nullable();
            $table->boolean('baixar_online')->nullable()->default(false)->comment('0=Não baixar, 1=Baixar, 2=recebendo zip, 3=executando
(Serve somente para integração online)');
            $table->string('token_online_processando', 80)->nullable();
            $table->string('job_id', 36)->nullable()->comment('Id do job de integração adicionado a fila no novo painel');
            $table->boolean('job_processando')->nullable()->comment('Processamento do job no novo painel');

            $table->unique(['fk_empresa'], 'fk_empresa_UNIQUE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodo_sincronizacao');
    }
};
