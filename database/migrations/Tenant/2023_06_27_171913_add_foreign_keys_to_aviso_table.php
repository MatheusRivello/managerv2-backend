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
        Schema::connection('tenant')->table('aviso', function (Blueprint $table) {
            $table->foreign(['id_filial'], 'fk_aviso_filial10')->references(['id'])->on('filial')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('aviso', function (Blueprint $table) {
            $table->dropForeign('fk_aviso_filial10');
        });
    }
};
