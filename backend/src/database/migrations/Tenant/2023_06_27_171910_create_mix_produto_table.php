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
        Schema::connection('tenant')->create('mix_produto', function (Blueprint $table) {
            $table->integer('id_produto')->index('fk_mix_produto_produto1_idx');
            $table->integer('id_cliente')->index('fk_mix_produto_cliente1_idx');
            $table->decimal('qtd_minima', 15, 3)->nullable()->default(0);
            $table->decimal('qtd_faturada', 15, 3)->nullable()->default(0);

            $table->primary(['id_produto', 'id_cliente']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('mix_produto');
    }
};
