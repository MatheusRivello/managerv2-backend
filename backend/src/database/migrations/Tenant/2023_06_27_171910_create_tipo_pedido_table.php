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
        Schema::connection('tenant')->create('tipo_pedido', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('id_retaguarda', 15)->unique('id_retaguarda_UNIQUE');
            $table->string('descricao', 30);
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('tipo_pedido');
    }
};
