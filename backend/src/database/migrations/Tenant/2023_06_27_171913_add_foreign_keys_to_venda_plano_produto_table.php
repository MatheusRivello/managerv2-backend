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
        Schema::connection('tenant')->table('venda_plano_produto', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_venda_plano_produto_cliente')->references(['id'])->on('cliente')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_produto'], 'fk_venda_plano_produto_produto')->references(['id'])->on('produto')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_filial'], 'fk_venda_plano_produto_filial')->references(['id'])->on('filial')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('venda_plano_produto', function (Blueprint $table) {
            $table->dropForeign('fk_venda_plano_produto_cliente');
            $table->dropForeign('fk_venda_plano_produto_produto');
            $table->dropForeign('fk_venda_plano_produto_filial');
        });
    }
};
