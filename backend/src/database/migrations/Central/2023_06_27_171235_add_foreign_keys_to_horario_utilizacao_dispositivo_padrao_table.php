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
        Schema::table('horario_utilizacao_dispositivo_padrao', function (Blueprint $table) {
            $table->foreign(['fk_empresa'], 'fk_empresa_padrao_key')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_horario'], 'fk_horario_padrao_key')->references(['id'])->on('horario')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('horario_utilizacao_dispositivo_padrao', function (Blueprint $table) {
            $table->dropForeign('fk_empresa_padrao_key');
            $table->dropForeign('fk_horario_padrao_key');
        });
    }
};
