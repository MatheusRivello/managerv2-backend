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
        Schema::connection('tenant')->table('rota', function (Blueprint $table) {
            $table->foreign(['id_filial'], 'rota_ibfk_1')->references(['id'])->on('filial')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rota', function (Blueprint $table) {
            $table->dropForeign('rota_ibfk_1');
        });
    }
};
