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
        Schema::connection('tenant')->create('pedido', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_pedido_filial1_idx');
            $table->string('id_retaguarda', 15)->nullable()->index('id_retaguarda_idx');
            $table->string('id_endereco', 25)->nullable()->index('id_pedido_endereco_fk_idx')->comment('O id do endereço será do id_retaguarda da tabela de endereço');
            $table->integer('id_cliente')->index('id_cliente');
            $table->integer('id_pedido_dispositivo')->nullable();
            $table->integer('id_tabela')->index('fk_pedido_tabela_idx');
            $table->integer('id_vendedor')->nullable()->index('fk_pedido_vendedor_idx');
            $table->integer('id_prazo_pgto')->index('fk_pedido_prazo_idx');
            $table->integer('id_forma_pgto')->index('fk_pedido_forma_idx');
            $table->integer('id_tipo_pedido')->index('fk_pedido_tipo_pedido1_idx');
            $table->integer('supervisor')->nullable()->default(0)->comment('Código do supervisor do cadastro do vendedor');
            $table->integer('gerente')->nullable()->default(0)->comment('Código do gerente do cadastro do vendedor');
            $table->decimal('valor_total', 15)->nullable()->default(0);
            $table->integer('qtde_itens')->nullable()->default(0);
            $table->string('nota_fiscal', 45)->nullable();
            $table->boolean('status')->nullable()->default(false);
            $table->boolean('status_entrega')->nullable()->default(false);
            $table->string('placa', 8)->nullable()->comment('Para armazenar a placa do veículo de entrega');
            $table->decimal('valor_st', 15, 4)->nullable()->default(0);
            $table->decimal('valor_ipi', 15, 4)->nullable()->default(0);
            $table->decimal('valor_acrescimo', 15, 4)->nullable()->default(0);
            $table->decimal('valor_desconto', 15, 4)->nullable()->default(0);
            $table->decimal('valorTotalComImpostos', 15, 4)->nullable()->default(0);
            $table->decimal('valorTotalBruto', 15, 4)->nullable()->default(0);
            $table->decimal('valorVerba', 15, 3)->nullable()->default(0);
            $table->integer('bonificacaoPorVerba')->default(0);
            $table->decimal('valor_frete', 15, 4)->nullable()->default(0);
            $table->decimal('valor_seguro', 15)->nullable()->default(0);
            $table->decimal('margem', 15, 4)->nullable()->default(0);
            $table->text('observacao')->nullable();
            $table->text('observacao_cliente')->nullable();
            $table->date('previsao_entrega')->nullable();
            $table->string('pedido_original', 15)->nullable()->comment('Código do Pedido Original, quando é realizado a copia do pedido');
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->string('precisao', 20)->nullable();
            $table->date('dt_entrega')->nullable();
            $table->dateTime('dt_inicial')->nullable()->comment('Data do Inicio da digitação do pedido no afv');
            $table->dateTime('dt_emissao')->nullable()->comment('data/hora que o pedido foi efetuado no dispositivo');
            $table->dateTime('dt_sinc_erp')->nullable()->comment('Esta data sera inserida quando o sistema local atualizar o id_retaguarda na nuvem');
            $table->dateTime('dt_cadastro')->useCurrent()->comment('data/hora que o pedido foi sincronizado');
            $table->string('origem', 10)->nullable();
            $table->boolean('notificacao_afv_manager')->nullable()->default(true)->comment('0=ja foi mostrado a notificacao,
1=mostrar notificação pendente,
2=mostrar notificação quando for sincronizar
');
            $table->boolean('enviarEmail')->nullable();
            $table->string('ip', 32)->nullable();
            $table->string('mac', 14);
            $table->dateTime('autorizacaoDataEnviada')->nullable();
            $table->string('autorizacaoSenha', 20)->nullable();
            $table->dateTime('autorizacaoaDataProcessada')->nullable();
            $table->string('distanciaCliente', 15)->nullable();
            $table->integer('motivoBloqueio')->nullable();
            $table->decimal('pes_bru', 15, 4)->nullable()->default(0);
            $table->decimal('pes_liq', 15, 4)->nullable()->default(0);
            $table->string('nfs_num', 20)->nullable();
            $table->smallInteger('tipo_frete')->default(0);
            $table->string('id_pedido_cliente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('pedido');
    }
};
