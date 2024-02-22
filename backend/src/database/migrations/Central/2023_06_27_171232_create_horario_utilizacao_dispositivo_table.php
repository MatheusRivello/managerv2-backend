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
        Schema::create('horario_utilizacao_dispositivo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('fk_empresa')->index('fk_empresa_horario_idx');
            $table->integer('fk_horario')->index('fk_horario_horario_idx');
            $table->unsignedInteger('fk_dispositivo')->index('fk_dispositivo_horario_idx');
            $table->integer('id_vendedor');
            $table->boolean('status')->default(false);

            $table->index(['fk_empresa', 'fk_horario', 'id_vendedor'], 'ids_unicos_horarios_utilizacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horario_utilizacao_dispositivo');
    }
};
