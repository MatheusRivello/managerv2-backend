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
        Schema::connection('tenant')->create('cliente_prazo_pgto', function (Blueprint $table) {
            $table->integer('id_cliente')->index('fk_cliente_prazo_pgto_cliente1_idx');
            $table->integer('id_prazo_pgto')->index('fk_cliente_prazo_pgto_prazo_pagamento_idx');

            $table->primary(['id_cliente', 'id_prazo_pgto']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('cliente_prazo_pgto');
    }
};
