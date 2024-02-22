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
        Schema::connection('tenant')->create('nota_fiscal_item', function (Blueprint $table) {
            $table->integer('id_filial')->index('fk_nota_fiscal_filial_idx');
            $table->integer('ped_num')->index('fk_nota_item_ped_num');
            $table->integer('id_produto')->index('fk_nota_fiscal_item_produto1_idx');
            $table->string('nfs_doc', 10)->nullable()->index('fk_nota_item_doc');
            $table->char('nfs_serie', 6)->nullable()->index('fk_nota_item_serie');
            $table->integer('nfs_status')->comment('1=Totalmente Atendido,2=Parcialmente Atendido,3=Não Atendido,4=Devolução,5=Bonificação,6=Aguardando Faturamento');
            $table->decimal('nfs_qtd', 15, 3)->default(0);
            $table->decimal('nfs_unitario', 15, 5)->default(0);
            $table->decimal('nfs_desconto', 15)->nullable()->default(0)->comment('% de desconto do item.');
            $table->decimal('nfs_descto', 15)->nullable()->default(0)->comment('Valor ( R$ ) de desconto do item.');
            $table->decimal('nfs_total', 15)->nullable()->default(0)->comment('Valor total do item ');
            $table->decimal('ped_qtd', 15, 3)->nullable()->default(0)->comment('Quantidade do produto no pedido de venda referente a nota.');
            $table->decimal('ped_total', 15)->nullable()->default(0)->comment('Valor total do item no pedido de venda referente a nota.');
            $table->decimal('nfs_custo', 15)->nullable()->default(0);
            $table->decimal('nfs_peso', 15, 4)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('nota_fiscal_item');
    }
};
