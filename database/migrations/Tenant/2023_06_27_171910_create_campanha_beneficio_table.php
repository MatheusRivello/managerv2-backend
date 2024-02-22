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
        Schema::connection('tenant')->create('campanha_beneficio', function (Blueprint $table) {
            $table->unsignedInteger('id_campanha');
            $table->string('id_retaguarda', 15);
            $table->smallInteger('tipo')->nullable();
            $table->string('codigo', 20)->nullable();
            $table->double('quantidade')->nullable();
            $table->double('percentual_desconto')->nullable();
            $table->tinyInteger('desconto_automatico')->nullable();
            $table->tinyInteger('bonificacao_automatica')->nullable();
            $table->tinyInteger('status')->nullable()->default(0);

            $table->primary(['id_campanha', 'id_retaguarda']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('campanha_beneficio');
    }
};
