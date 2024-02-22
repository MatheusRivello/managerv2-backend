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
        Schema::connection('tenant')->table('cliente', function (Blueprint $table) {
            $table->foreign(['id_filial'], 'fk_cliente_filial')->references(['id'])->on('filial')->onUpdate('CASCADE');
            $table->foreign(['id_status'], 'fk_cliente_status_cliente1')->references(['id'])->on('status_cliente')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('cliente', function (Blueprint $table) {
            $table->dropForeign('fk_cliente_filial');
            $table->dropForeign('fk_cliente_status_cliente1');
        });
    }
};
