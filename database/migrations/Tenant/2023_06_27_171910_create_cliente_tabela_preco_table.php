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
        Schema::connection('tenant')->create('cliente_tabela_preco', function (Blueprint $table) {
            $table->integer('id_cliente');
            $table->integer('id_tabela');

            $table->primary(['id_cliente', 'id_tabela']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('cliente_tabela_preco');
    }
};
