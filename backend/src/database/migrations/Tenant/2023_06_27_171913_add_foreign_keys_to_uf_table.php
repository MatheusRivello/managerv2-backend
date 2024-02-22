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
        Schema::connection('tenant')->table('uf', function (Blueprint $table) {
            $table->foreign(['id_regiao'], 'fk_uf_regiao1')->references(['id'])->on('regiao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('uf', function (Blueprint $table) {
            $table->dropForeign('fk_uf_regiao1');
        });
    }
};
