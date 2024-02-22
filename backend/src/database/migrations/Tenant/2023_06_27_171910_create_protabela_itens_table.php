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
        Schema::connection('tenant')->create('protabela_itens', function (Blueprint $table) {
            $table->integer('id_produto')->index('fk_protabela_itens_produto1_idx');
            $table->integer('id_protabela_preco')->index('fk_protabela_itens_protabela_preco1_idx');
            $table->decimal('unitario', 15, 4)->nullable()->comment('preço unitario do produto na tabela');
            $table->boolean('status')->default(true);
            $table->decimal('qevendamax', 15, 3)->nullable()->default(0)->comment('Quantidade maxima para venda do produto na tabela');
            $table->decimal('qevendamin', 15, 3)->nullable()->default(0)->comment('Quantidade minima para venda do produto na tabela');
            $table->decimal('desconto', 15)->nullable();
            $table->decimal('desconto2', 15)->nullable();
            $table->decimal('desconto3', 15)->nullable();

            $table->primary(['id_produto', 'id_protabela_preco']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('protabela_itens');
    }
};
