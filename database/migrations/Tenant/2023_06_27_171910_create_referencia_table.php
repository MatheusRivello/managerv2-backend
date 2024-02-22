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
        Schema::connection('tenant')->create('referencia', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sequencia');
            $table->string('fornecedor', 100)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('desde', 20)->nullable();
            $table->string('conceito', 45)->nullable();
            $table->double('limite', 14, 2)->nullable()->default(0);
            $table->boolean('pontual')->nullable();
            $table->double('ultima_fatura_valor', 14, 2)->nullable()->default(0);
            $table->string('ultima_fatura_data', 20)->nullable();
            $table->double('maior_fatura_valor', 14, 2)->nullable()->default(0);
            $table->string('maior_fatura_data', 20)->nullable();
            $table->double('maior_acumulo_valor', 14, 2)->nullable()->default(0);
            $table->string('maior_acumulo_data', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('referencia');
    }
};
