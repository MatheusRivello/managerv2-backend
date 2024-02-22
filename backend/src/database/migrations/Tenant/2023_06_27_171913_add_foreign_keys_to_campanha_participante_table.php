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
        Schema::connection('tenant')->table('campanha_participante', function (Blueprint $table) {
            $table->foreign(['id_campanha'], 'fk_campanha_participante')->references(['id'])->on('campanha')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('campanha_participante', function (Blueprint $table) {
            $table->dropForeign('fk_campanha_participante');
        });
    }
};
