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
        Schema::connection('tenant')->create('sincronizacao_loja_integrada', function (Blueprint $table) {
            $table->integer('id_produto')->unique('sincronizacao_loja_integrada_un');
            $table->timestamp('sincronizado_em')->nullable();
            $table->timestamp('atualizado_em')->nullable();

            $table->index(['id_produto'], 'sincronizacao_loja_integrada_FK');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('sincronizacao_loja_integrada');
    }
};
