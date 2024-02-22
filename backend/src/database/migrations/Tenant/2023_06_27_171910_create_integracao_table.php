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
        Schema::connection('tenant')->create('integracao', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('integrador')->comment('1=Vendas Externas
2=Xtech
...');
            $table->integer('tipo')->nullable()->comment('1=Cliente
2=Produto
');
            $table->integer('id_interno');
            $table->string('id_externo', 30)->nullable();
            $table->string('campo_extra_1', 45)->nullable();
            $table->string('campo_extra_2', 45)->nullable();
            $table->string('campo_extra_3', 45)->nullable();
            $table->string('ultimo_status', 45)->nullable();
            $table->dateTime('dt_modificado')->nullable()->useCurrent();

            $table->unique(['integrador', 'tipo', 'id_interno'], 'integracao_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('integracao');
    }
};
