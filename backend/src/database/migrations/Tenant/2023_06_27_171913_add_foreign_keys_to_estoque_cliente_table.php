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
        Schema::connection('tenant')->table('estoque_cliente', function (Blueprint $table) {
            $table->foreign(['id_filial'], 'estoque_cliente_ibfk_1')->references(['id'])->on('filial')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_produto'], 'estoque_cliente_ibfk_3')->references(['id'])->on('produto')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_cliente'], 'estoque_cliente_ibfk_2')->references(['id'])->on('cliente')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_vendedor'], 'estoque_cliente_ibfk_4')->references(['id'])->on('vendedor')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('estoque_cliente', function (Blueprint $table) {
            $table->dropForeign('estoque_cliente_ibfk_1');
            $table->dropForeign('estoque_cliente_ibfk_3');
            $table->dropForeign('estoque_cliente_ibfk_2');
            $table->dropForeign('estoque_cliente_ibfk_4');
        });
    }
};
