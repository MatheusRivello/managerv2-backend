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
        Schema::connection('tenant')->create('meta_detalhe', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_meta')->index('fk_meta_detalhe_meta1_idx');
            $table->integer('ordem')->nullable()->default(0)->comment('Ordenação da informação ou id incremental próprio');
            $table->string('descricao', 100);
            $table->integer('tot_cli_cadastrados')->nullable()->default(0);
            $table->integer('tot_cli_atendidos')->nullable()->default(0);
            $table->decimal('percent_tot_cli_atendidos')->nullable()->default(0);
            $table->decimal('tot_qtd_ven', 15)->nullable()->default(0);
            $table->decimal('tot_peso_ven', 15)->nullable()->default(0);
            $table->decimal('percent_tot_peso_ven', 15)->nullable()->default(0);
            $table->decimal('tot_val_ven', 15)->nullable()->default(0);
            $table->decimal('percent_tot_val_ven', 15)->nullable()->default(0);
            $table->decimal('objetivo_vendas', 15)->nullable()->default(0);
            $table->decimal('percent_atingido', 15)->nullable()->default(0);
            $table->decimal('tendencia_vendas', 15)->nullable()->default(0);
            $table->decimal('percent_tendencia_ven', 15)->nullable()->default(0);
            $table->decimal('objetivo_clientes', 15)->nullable()->default(0);
            $table->decimal('numero_cli_falta_atender', 15)->nullable()->default(0);
            $table->decimal('ped_a_faturar', 15)->nullable()->default(0);
            $table->decimal('prazo_medio', 15)->nullable()->default(0);
            $table->decimal('percent_desconto', 15)->nullable()->default(0);
            $table->decimal('tot_desconto', 15)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('meta_detalhe');
    }
};
