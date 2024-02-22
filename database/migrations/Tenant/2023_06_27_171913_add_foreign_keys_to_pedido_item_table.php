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
        Schema::connection('tenant')->table('pedido_item', function (Blueprint $table) {
            $table->foreign(['id_pedido'], 'fk_pedido_item_pedido')->references(['id'])->on('pedido')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_tabela'], 'fk_pedido_item_tabela')->references(['id'])->on('protabela_preco')->onUpdate('CASCADE');
            $table->foreign(['id_produto'], 'fk_pedido_item_produto')->references(['id'])->on('produto')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('pedido_item', function (Blueprint $table) {
            $table->dropForeign('fk_pedido_item_pedido');
            $table->dropForeign('fk_pedido_item_tabela');
            $table->dropForeign('fk_pedido_item_produto');
        });
    }
};
