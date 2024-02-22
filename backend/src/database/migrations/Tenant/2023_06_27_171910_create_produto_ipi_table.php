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
        Schema::connection('tenant')->create('produto_ipi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_produto')->index('fk_produto_ipi_produto1_idx');
            $table->decimal('tipi_mva', 15)->nullable()->default(0)->comment('% MVA Original');
            $table->decimal('tipi_mva_simples', 15)->nullable()->default(0)->comment('% MVA Original - Opt. Simples');
            $table->decimal('tipi_mva_fe_nac', 15)->nullable()->default(0)->comment('% MVA Interestadual de 12%');
            $table->decimal('tipi_mva_fe_imp', 15)->nullable()->default(0)->comment('% MVA Interestadual de 4%');
            $table->integer('tipi_tpcalc')->nullable()->default(0)->comment('Tipo de Cálculo de calculo usado para o IPI 
0 = Percentual
1 = Pauta Valor
2 = Pauta Peso');
            $table->decimal('tipi_aliquota', 15)->nullable()->default(0)->comment('% de Aliquota do IPI');
            $table->decimal('tipi_pauta', 15, 4)->nullable()->default(0)->comment('Valor da Pauta para calculo do IPI');
            $table->boolean('calcula_ipi')->nullable()->default(false);

            $table->unique(['id_produto'], 'id_produto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('produto_ipi');
    }
};
