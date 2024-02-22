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
        Schema::connection('tenant')->create('vendedor_cliente', function (Blueprint $table) {
            $table->integer('id_cliente')->index('fk_vendedor_cliente_cliente1_idx');
            $table->integer('id_vendedor')->index('fk_vendedor_cliente_vendedor1_idx');

            $table->primary(['id_cliente', 'id_vendedor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('vendedor_cliente');
    }
};
