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
        Schema::connection('tenant')->create('cliente', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_cliente_filial1_idx');
            $table->string('id_retaguarda', 15)->nullable()->index('cliente_ibfk_2_idx');
            $table->string('id_tabela_preco', 15)->nullable();
            $table->string('id_prazo_pgto', 15)->nullable();
            $table->string('id_forma_pgto', 15)->nullable();
            $table->integer('id_status')->nullable()->index('fk_cliente_status_cliente1_idx');
            $table->string('razao_social', 60)->nullable();
            $table->string('nome_fantasia', 60)->nullable();
            $table->string('cnpj', 14)->comment('0= Jurídica, 1= Física');
            $table->string('senha', 32)->nullable();
            $table->string('email', 100)->nullable();
            $table->dateTime('codigo_tempo')->nullable();
            $table->string('codigo_senha', 40)->nullable();
            $table->string('id_atividade', 15)->nullable();
            $table->string('telefone', 15)->nullable();
            $table->string('tipo', 1)->nullable()->default('0')->comment('0=JURIDICA, 1=FISICA');
            $table->string('tipo_contribuinte', 5)->nullable();
            $table->string('site', 100)->nullable();
            $table->string('email_nfe', 100)->nullable();
            $table->decimal('limite_credito', 15, 4)->nullable();
            $table->decimal('saldo', 15, 4)->nullable()->default(0);
            $table->decimal('saldo_credor', 15, 4)->default(0)->comment('Saldo Credor do ERP');
            $table->boolean('sinc_erp')->nullable()->default(false)->comment('1 = ATUALIZAR
0= NÃO ATUALIZAR ou ATUALIZADO

QUANDO FOR IGUAL A (1) SERÁ PARA O SIG PEGAR AS INFORMAÇÕES E ATUALIZAR, SE FOR IGUAL A (0) É PORQUE JÁ ATUALIZOU.');
            $table->text('observacao')->nullable();
            $table->char('intervalo_visita', 1)->nullable();
            $table->dateTime('dt_ultima_visita')->nullable();
            $table->dateTime('dt_cadastro')->nullable()->comment('DATA DO CADASTRO NO DISPOSITIVO');
            $table->dateTime('dt_modificado')->nullable()->comment('DATA DO MODIFICADO NO DISPOSITIVO');
            $table->boolean('bloqueia_forma_pgto')->nullable()->default(false);
            $table->boolean('bloqueia_prazo_pgto')->nullable()->default(false);
            $table->boolean('bloqueia_tabela')->nullable()->default(false);
            $table->integer('id_mobile')->nullable();
            $table->string('inscricao_municipal', 45)->nullable();
            $table->string('inscricao_rg', 45)->nullable();
            $table->integer('ven_cod')->nullable()->comment('Código do vendedor padrão do cliente da tabela CLICLIENTES.');
            $table->integer('integra_web')->nullable()->default(1);
            $table->decimal('atraso_tot', 15)->default(0);
            $table->decimal('avencer', 15)->default(0);
            $table->integer('media_dias_atraso')->nullable()->default(0);
            $table->date('dt_ultima_compra')->nullable();

            $table->unique(['id_retaguarda', 'id_filial'], 'ids_unicos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('cliente');
    }
};
