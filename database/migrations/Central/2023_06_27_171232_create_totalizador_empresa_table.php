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
        Schema::create('totalizador_empresa', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedInteger('fk_empresa')->index('fk_totalizado_empresa_empresa1_idx');
            $table->date('data');
            $table->integer('qtd_pedido')->default(0);
            $table->decimal('valor_pedido', 15)->nullable()->default(0);
            $table->decimal('peso_bruto', 15)->nullable()->default(0);
            $table->decimal('peso_liquido', 15)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('totalizador_empresa');
    }
};
