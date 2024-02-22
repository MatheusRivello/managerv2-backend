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
        Schema::connection('tenant')->table('nota_fiscal', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_nota_fiscal_cliente')->references(['id'])->on('cliente')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_vendedor'], 'fk_nota_fiscal_vendedor1')->references(['id'])->on('vendedor')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_filial'], 'fk_nota_fiscal_filial22')->references(['id'])->on('filial')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('nota_fiscal', function (Blueprint $table) {
            $table->dropForeign('fk_nota_fiscal_cliente');
            $table->dropForeign('fk_nota_fiscal_vendedor1');
            $table->dropForeign('fk_nota_fiscal_filial22');
        });
    }
};
