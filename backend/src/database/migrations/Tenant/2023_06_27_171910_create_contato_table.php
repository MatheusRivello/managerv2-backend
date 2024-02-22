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
        Schema::connection('tenant')->create('contato', function (Blueprint $table) {
            $table->integer('id_cliente')->index('fk_contato_cliente1_idx');
            $table->boolean('con_cod');
            $table->string('telefone', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nome', 100)->nullable();
            $table->string('aniversario', 10)->nullable();
            $table->string('hobby', 100)->nullable();
            $table->boolean('sinc_erp')->nullable()->default(false);

            $table->primary(['id_cliente', 'con_cod']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('contato');
    }
};
