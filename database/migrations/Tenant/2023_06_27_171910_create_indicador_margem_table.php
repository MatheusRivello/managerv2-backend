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
        Schema::connection('tenant')->create('indicador_margem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_indicador_margem_filial1_idx');
            $table->boolean('nivel');
            $table->decimal('de', 15)->default(0);
            $table->decimal('ate', 15)->default(0);
            $table->integer('indice')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('indicador_margem');
    }
};
