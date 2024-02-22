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
        Schema::connection('tenant')->table('ddd', function (Blueprint $table) {
            $table->foreign(['id_uf'], 'fk_ddd_uf1')->references(['id'])->on('uf')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('ddd', function (Blueprint $table) {
            $table->dropForeign('fk_ddd_uf1');
        });
    }
};
