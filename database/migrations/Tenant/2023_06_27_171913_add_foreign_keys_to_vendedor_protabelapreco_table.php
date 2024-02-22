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
        Schema::connection('tenant')->table('vendedor_protabelapreco', function (Blueprint $table) {
            $table->foreign(['id_protabela_preco'], 'fk_vendedor_protabelapreco_protabela_preco1')->references(['id'])->on('protabela_preco');
            $table->foreign(['id_vendedor'], 'fk_vendedor_protabelapreco_vendedor1')->references(['id'])->on('vendedor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('vendedor_protabelapreco', function (Blueprint $table) {
            $table->dropForeign('fk_vendedor_protabelapreco_protabela_preco1');
            $table->dropForeign('fk_vendedor_protabelapreco_vendedor1');
        });
    }
};
