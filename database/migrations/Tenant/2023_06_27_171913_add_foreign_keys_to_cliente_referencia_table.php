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
        Schema::connection('tenant')->table('cliente_referencia', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_cliente_referencia_cliente1')->references(['id'])->on('cliente')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_referencia'], 'fk_cliente_referencia_referencia1')->references(['id'])->on('referencia')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('cliente_referencia', function (Blueprint $table) {
            $table->dropForeign('fk_cliente_referencia_cliente1');
            $table->dropForeign('fk_cliente_referencia_referencia1');
        });
    }
};
