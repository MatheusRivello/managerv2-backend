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
        Schema::connection('tenant')->table('log', function (Blueprint $table) {
            $table->foreign(['id_filial'], 'fk_log_filial1')->references(['id'])->on('filial');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('log', function (Blueprint $table) {
            $table->dropForeign('fk_log_filial1');
        });
    }
};