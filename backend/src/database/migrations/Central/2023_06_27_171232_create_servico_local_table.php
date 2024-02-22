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
        Schema::create('servico_local', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('fk_empresa')->index('fk_servico_local_empresa1_idx');
            $table->dateTime('dt_atualizado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servico_local');
    }
};
