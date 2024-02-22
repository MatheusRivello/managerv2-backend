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
        Schema::connection('tenant')->create('venda_plano_produto', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('id_filial')->nullable()->index('fk_venda_plano_produto_filial_idx');
            $table->integer('id_cliente')->index('fk_venda_plano_produto_cliente_idx');
            $table->integer('id_produto')->index('fk_venda_plano_produto_produto_idx');
            $table->string('nfs_num', 16);
            $table->double('qtd_contratada')->default(0);
            $table->double('qtd_entregue')->default(0);
            $table->double('qtd_disponivel')->default(0);
            $table->decimal('valor_unitario', 15, 4)->default(0);
            $table->string('unidade', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('venda_plano_produto');
    }
};
