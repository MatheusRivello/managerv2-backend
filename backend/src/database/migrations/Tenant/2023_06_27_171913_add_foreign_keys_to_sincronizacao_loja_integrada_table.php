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
        Schema::connection('tenant')->table('sincronizacao_loja_integrada', function (Blueprint $table) {
            $table->foreign(['id_produto'], 'sincronizacao_loja_integrada_FK')->references(['id'])->on('produto')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('sincronizacao_loja_integrada', function (Blueprint $table) {
            $table->dropForeign('sincronizacao_loja_integrada_FK');
        });
    }
};
