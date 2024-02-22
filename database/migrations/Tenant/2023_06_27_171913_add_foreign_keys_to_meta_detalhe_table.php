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
        Schema::connection('tenant')->table('meta_detalhe', function (Blueprint $table) {
            $table->foreign(['id_meta'], 'fk_meta_detalhe_meta')->references(['id'])->on('meta')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('meta_detalhe', function (Blueprint $table) {
            $table->dropForeign('fk_meta_detalhe_meta');
        });
    }
};
