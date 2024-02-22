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
        Schema::connection('tenant')->create('cliente_referencia', function (Blueprint $table) {
            $table->integer('id_cliente')->index('fk_cliente_referencia_cliente1_idx');
            $table->integer('id_referencia')->index('fk_cliente_referencia_referencia1_idx');

            $table->primary(['id_cliente', 'id_referencia']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('cliente_referencia');
    }
};
