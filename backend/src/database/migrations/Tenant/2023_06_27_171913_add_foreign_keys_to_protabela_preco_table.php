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
        Schema::connection('tenant')->table('protabela_preco', function (Blueprint $table) {
            $table->foreign(['id_filial'], 'fk_protabela_preco_filial')->references(['id'])->on('filial')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('protabela_preco', function (Blueprint $table) {
            $table->dropForeign('fk_protabela_preco_filial');
        });
    }
};
