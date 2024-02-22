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
        Schema::table('totalizador_empresa', function (Blueprint $table) {
            $table->foreign(['fk_empresa'], 'fk_totalizado_empresa_empresa1')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('totalizador_empresa', function (Blueprint $table) {
            $table->dropForeign('fk_totalizado_empresa_empresa1');
        });
    }
};
