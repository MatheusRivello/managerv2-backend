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
        Schema::table('horario_utilizacao_dispositivo', function (Blueprint $table) {
            $table->foreign(['fk_dispositivo'], 'fk_dispositivo_horario_key')->references(['id'])->on('dispositivo')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_horario'], 'fk_horario_horario_key')->references(['id'])->on('horario')->onUpdate('CASCADE');
            $table->foreign(['fk_empresa'], 'fk_empresa_horario_key')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('horario_utilizacao_dispositivo', function (Blueprint $table) {
            $table->dropForeign('fk_dispositivo_horario_key');
            $table->dropForeign('fk_horario_horario_key');
            $table->dropForeign('fk_empresa_horario_key');
        });
    }
};
