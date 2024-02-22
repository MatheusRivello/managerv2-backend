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
        Schema::connection('tenant')->create('produto_descto_qtd', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_produto')->index('fk_produto_descto_qtd_produto1_idx');
            $table->integer('id_protabela_preco')->index('fk_produto_descto_qtd_protabela_preco1_idx');
            $table->integer('quantidade')->nullable()->default(0);
            $table->decimal('desconto', 15)->nullable()->default(0)->comment('% de desconto.');

            $table->unique(['id_produto', 'id_protabela_preco', 'quantidade'], 'ids_unicos_pro_descto_qtd');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('produto_descto_qtd');
    }
};
