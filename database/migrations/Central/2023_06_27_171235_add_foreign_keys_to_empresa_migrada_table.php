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
        Schema::table('empresa_migrada', function (Blueprint $table) {
            $table->foreign(['fk_empresa'], 'empresa_migrada_FK')->references(['id'])->on('empresa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresa_migrada', function (Blueprint $table) {
            $table->dropForeign('empresa_migrada_FK');
        });
    }
};
