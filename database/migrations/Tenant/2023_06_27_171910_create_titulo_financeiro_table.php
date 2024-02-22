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
        Schema::connection('tenant')->create('titulo_financeiro', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_cliente')->index('fk_titulo_financeiro_cliente1_idx');
            $table->integer('id_forma_pgto')->nullable()->index('id_forma_pgto_titulo_idx');
            $table->unsignedInteger('id_vendedor')->nullable()->index('titulo_financeiro_vendedor_idx');
            $table->text('descricao');
            $table->string('id_retaguarda', 25);
            $table->string('numero_doc', 20);
            $table->string('tipo_titulo', 30);
            $table->string('parcela', 5);
            $table->date('dt_vencimento');
            $table->date('dt_vencimento_orig')->nullable();
            $table->date('dt_pagamento')->nullable();
            $table->date('dt_competencia')->nullable();
            $table->date('dt_emissao')->nullable();
            $table->decimal('valor', 15, 4);
            $table->decimal('multa_juros', 15, 4)->nullable();
            $table->boolean('status')->default(false)->comment('0=EM ABERTO,1=PAGO');
            $table->decimal('valor_devolucao', 15, 4)->default(0);
            $table->decimal('valor_original', 15, 4)->nullable()->default(0);
            $table->string('linha_digitavel', 100)->nullable();
            $table->string('nosso_numero')->nullable();

            $table->unique(['id_cliente', 'id_retaguarda'], 'ids_unicos_titulo_financeiro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('titulo_financeiro');
    }
};
