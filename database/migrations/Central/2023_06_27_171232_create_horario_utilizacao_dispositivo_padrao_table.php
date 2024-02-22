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
        Schema::create('horario_utilizacao_dispositivo_padrao', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('fk_empresa')->index('fk_empresa_horario_padrao_idx');
            $table->integer('fk_horario')->index('fk_horario_padrao_idx');
            $table->boolean('status_padrao')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horario_utilizacao_dispositivo_padrao');
    }
};
