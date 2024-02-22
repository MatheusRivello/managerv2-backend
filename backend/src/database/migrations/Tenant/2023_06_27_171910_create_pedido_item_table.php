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
        Schema::connection('tenant')->create('pedido_item', function (Blueprint $table) {
            $table->integer('id_pedido')->index('fk_pedido_item_pedido1_idx');
            $table->integer('numero_item');
            $table->integer('id_produto')->index('fk_pedido_item_produto_idx');
            $table->integer('id_tabela')->index('fk_pedido_item_tabela_idx');
            $table->string('embalagem', 20)->nullable();
            $table->double('quantidade')->nullable()->default(0);
            $table->decimal('valor_total', 15, 4)->nullable()->default(0);
            $table->decimal('valor_st', 15, 4)->nullable()->default(0);
            $table->decimal('valor_ipi', 15, 4)->nullable()->default(0);
            $table->decimal('valor_tabela', 15, 4)->nullable()->default(0);
            $table->decimal('valor_unitario', 15, 4)->nullable()->default(0);
            $table->decimal('valor_desconto', 15, 4)->nullable()->default(0);
            $table->decimal('cashback', 15)->default(0);
            $table->decimal('unitario_cashback', 15)->default(0);
            $table->decimal('valor_frete', 15, 4)->nullable()->default(0);
            $table->decimal('valor_seguro', 15, 4)->nullable()->default(0);
            $table->decimal('valorVerba', 15, 3)->nullable()->default(0);
            $table->decimal('valorTotalComImpostos', 15, 4)->nullable()->default(0);
            $table->decimal('valor_icms', 15, 4)->nullable()->default(0);
            $table->decimal('ped_desqtd', 15)->nullable()->default(0)->comment('Percentual de Desconto Aplicado por Quantidade');
            $table->decimal('percentualVerba', 15, 3)->nullable()->default(0);
            $table->decimal('base_st', 15, 4)->nullable()->default(0);
            $table->decimal('percentualdesconto', 15, 4)->nullable()->default(0);
            $table->boolean('tipoacrescimodesconto')->nullable()->default(false);
            $table->boolean('status')->nullable()->default(false);
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();
            $table->char('unidvenda', 8)->nullable();
            $table->decimal('custo', 15, 4)->nullable()->default(0);
            $table->decimal('margem', 15, 4)->nullable()->default(0);
            $table->decimal('pes_bru', 15, 4)->nullable()->default(0);
            $table->decimal('pes_liq', 15, 4)->nullable()->default(0);

            $table->primary(['id_pedido', 'numero_item']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('pedido_item');
    }
};
