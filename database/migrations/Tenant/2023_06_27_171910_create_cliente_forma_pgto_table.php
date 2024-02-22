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
        Schema::connection('tenant')->create('cliente_forma_pgto', function (Blueprint $table) {
            $table->integer('id_cliente')->index('fk_cliente_forma_pgto_cliente1_idx');
            $table->integer('id_forma_pgto')->index('fk_cliente_forma_pgto_forma_pagamento1_idx');

            $table->primary(['id_cliente', 'id_forma_pgto']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('cliente_forma_pgto');
    }
};
