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
        Schema::connection('tenant')->create('aviso', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_aviso_filial1_idx');
            $table->text('descricao');
            $table->dateTime('dt_inicio')->nullable();
            $table->dateTime('dt_fim')->nullable();
            $table->boolean('tipo')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('aviso');
    }
};
