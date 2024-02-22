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
        Schema::connection('tenant')->table('produto_descto_qtd', function (Blueprint $table) {
            $table->foreign(['id_produto'], 'fk_produto_descto_qtd_produto')->references(['id'])->on('produto')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_protabela_preco'], 'fk_produto_descto_qtd_protabela_preco')->references(['id'])->on('protabela_preco')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('produto_descto_qtd', function (Blueprint $table) {
            $table->dropForeign('fk_produto_descto_qtd_produto');
            $table->dropForeign('fk_produto_descto_qtd_protabela_preco');
        });
    }
};
