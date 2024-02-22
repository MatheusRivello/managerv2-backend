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
        Schema::connection('tenant')->table('protabela_itens', function (Blueprint $table) {
            $table->foreign(['id_produto'], 'fk_protabela_itens_produto1')->references(['id'])->on('produto')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_protabela_preco'], 'fk_protabela_itens_protabela_preco1')->references(['id'])->on('protabela_preco')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('protabela_itens', function (Blueprint $table) {
            $table->dropForeign('fk_protabela_itens_produto1');
            $table->dropForeign('fk_protabela_itens_protabela_preco1');
        });
    }
};
