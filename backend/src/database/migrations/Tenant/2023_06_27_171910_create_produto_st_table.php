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
        Schema::connection('tenant')->create('produto_st', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_produto')->index('fk_produto_st_produto1_idx');
            $table->string('tipo_contribuinte', 15);
            $table->char('uf', 2);
            $table->decimal('aliquota_icms', 7, 4)->nullable()->default(0)->comment('Percentual ICMS – Imposto sobre cisrculação demercadorias');
            $table->decimal('aliquota_icms_st', 7, 4)->nullable()->default(0)->comment('Percentual de Substituição tributária de ICMS');
            $table->decimal('valor_referencia', 10, 4)->nullable()->default(0);
            $table->tinyInteger('class_pauta_mva')->default(0);
            $table->decimal('pauta', 10, 4)->nullable()->default(0)->comment('Caso não tenha passar zero');
            $table->boolean('tipo_mva')->default(false);
            $table->decimal('mva', 7, 4)->nullable()->default(0)->comment('Margem de Valor Agregado ou IVA (Indice de Valor Adicionado)');
            $table->decimal('reducao_icms', 7, 4)->nullable()->default(0)->comment('Caso não tenha passar zero');
            $table->decimal('reducao_icms_st', 7, 4)->nullable()->default(0)->comment('Caso não tenha passar zero');
            $table->string('modo_calculo', 3)->nullable()->default('0');
            $table->char('calcula_ipi', 1)->nullable()->default('0');
            $table->boolean('frete_icms')->nullable()->default(false);
            $table->boolean('frete_ipi')->nullable()->default(false);
            $table->boolean('incide_ipi_base')->nullable();

            $table->unique(['tipo_contribuinte', 'uf', 'id_produto'], 'ids_unicos_p_st');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('produto_st');
    }
};
