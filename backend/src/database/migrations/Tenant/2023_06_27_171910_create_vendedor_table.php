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
        Schema::connection('tenant')->create('vendedor', function (Blueprint $table) {
            $table->integer('id')->primary()->comment('Pega o valor do campo VEN_COD da tabela CLIVENDEDORES');
            $table->string('nome', 100);
            $table->boolean('status');
            $table->string('usuario', 45)->nullable();
            $table->string('senha', 45)->nullable();
            $table->integer('supervisor')->nullable();
            $table->integer('gerente')->nullable();
            $table->integer('sequencia_pedido')->nullable()->default(0);
            $table->boolean('tipo')->nullable()->default(false)->comment('0=Vendedor
1=Supervisor
2=Gerente');
            $table->decimal('saldoVerba', 15, 3)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('vendedor');
    }
};
