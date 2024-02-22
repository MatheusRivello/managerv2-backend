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
        Schema::connection('tenant')->table('rastro', function (Blueprint $table) {
            $table->foreign(['id_vendedor'], 'fk_rastro_vendedor')->references(['id'])->on('vendedor')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('rastro', function (Blueprint $table) {
            $table->dropForeign('fk_rastro_vendedor');
        });
    }
};
