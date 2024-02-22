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
        Schema::connection('tenant')->create('prazo_pagamento', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('id_retaguarda', 15)->unique('ids_unicos_prazo_pgto');
            $table->string('descricao', 100);
            $table->decimal('variacao', 7, 4)->default(0);
            $table->decimal('valor_min', 15, 4)->nullable()->default(0);
            $table->boolean('status')->default(true)->comment('0 = Desativado
1 = Ativado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('prazo_pagamento');
    }
};
