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
        Schema::connection('tenant')->create('vendedor_protabelapreco', function (Blueprint $table) {
            $table->integer('id_protabela_preco')->index('fk_vendedor_protabelapreco_protabela_preco1_idx');
            $table->integer('id_vendedor')->index('fk_vendedor_protabelapreco_vendedor1_idx');

            $table->primary(['id_protabela_preco', 'id_vendedor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('vendedor_protabelapreco');
    }
};
