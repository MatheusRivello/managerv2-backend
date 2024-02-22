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
        Schema::connection('tenant')->create('meta', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_meta_filial1_idx');
            $table->integer('id_vendedor')->index('fk_meta_vendedor1_idx');
            $table->string('id_retaguarda', 15);
            $table->string('descricao', 100);
            $table->decimal('tot_qtd_ven', 15)->nullable()->default(0)->comment('total_qtd_vendida');
            $table->decimal('tot_peso_ven', 15)->nullable()->default(0);
            $table->decimal('objetivo_vendas', 15)->nullable()->default(0);
            $table->decimal('tot_val_ven', 15)->nullable()->default(0)->comment('Total valor vendido');
            $table->decimal('percent_atingido', 15)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('meta');
    }
};
