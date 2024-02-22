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
        Schema::connection('tenant')->create('atividade', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_atividade_filial1_idx');
            $table->string('id_retaguarda', 15);
            $table->string('descricao', 60);
            $table->boolean('status')->default(true);

            $table->unique(['id_retaguarda', 'id_filial'], 'ids_unicos_atividade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('atividade');
    }
};
