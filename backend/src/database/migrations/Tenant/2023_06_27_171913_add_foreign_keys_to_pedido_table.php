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
        Schema::connection('tenant')->table('pedido', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_pedido_cliente')->references(['id'])->on('cliente')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_forma_pgto'], 'fk_pedido_forma')->references(['id'])->on('forma_pagamento')->onUpdate('CASCADE');
            $table->foreign(['id_tabela'], 'fk_pedido_tabela')->references(['id'])->on('protabela_preco')->onUpdate('CASCADE');
            $table->foreign(['id_vendedor'], 'fk_pedido_vendedor')->references(['id'])->on('vendedor')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_filial'], 'fk_pedido_filial')->references(['id'])->on('filial')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_prazo_pgto'], 'fk_pedido_prazo')->references(['id'])->on('prazo_pagamento')->onUpdate('CASCADE');
            $table->foreign(['id_tipo_pedido'], 'fk_pedido_tipo_pedido1')->references(['id'])->on('tipo_pedido');
            $table->foreign(['id_endereco'], 'id_pedido_endereco_fk')->references(['id_retaguarda'])->on('endereco')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('pedido', function (Blueprint $table) {
            $table->dropForeign('fk_pedido_cliente');
            $table->dropForeign('fk_pedido_forma');
            $table->dropForeign('fk_pedido_tabela');
            $table->dropForeign('fk_pedido_vendedor');
            $table->dropForeign('fk_pedido_filial');
            $table->dropForeign('fk_pedido_prazo');
            $table->dropForeign('fk_pedido_tipo_pedido1');
            $table->dropForeign('id_pedido_endereco_fk');
        });
    }
};
