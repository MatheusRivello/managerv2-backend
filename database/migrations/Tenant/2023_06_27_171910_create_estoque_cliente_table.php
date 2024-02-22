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
        Schema::connection('tenant')->create('estoque_cliente', function (Blueprint $table) {
            $table->integer('id_filial')->index('id_filial');
            $table->integer('id_vendedor')->index('id_vendedor');
            $table->integer('id_cliente')->index('id_cliente');
            $table->integer('id_produto')->index('id_produto');
            $table->integer('quantidade');
            $table->decimal('valor_gondula', 15);
            $table->decimal('markup', 15);
            $table->dateTime('dt_coleta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('estoque_cliente');
    }
};
