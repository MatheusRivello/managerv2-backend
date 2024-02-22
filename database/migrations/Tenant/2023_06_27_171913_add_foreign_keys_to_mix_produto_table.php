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
        Schema::connection('tenant')->table('mix_produto', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_mix_produto_cliente1')->references(['id'])->on('cliente');
            $table->foreign(['id_produto'], 'fk_mix_produto_produto1')->references(['id'])->on('produto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('mix_produto', function (Blueprint $table) {
            $table->dropForeign('fk_mix_produto_cliente1');
            $table->dropForeign('fk_mix_produto_produto1');
        });
    }
};
