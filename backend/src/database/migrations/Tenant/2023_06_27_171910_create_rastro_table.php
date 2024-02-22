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
        Schema::connection('tenant')->create('rastro', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_vendedor')->index('fk_rastro_vendedor1_idx');
            $table->date('data')->nullable();
            $table->time('hora')->nullable();
            $table->string('latitude', 30)->nullable();
            $table->string('longitude', 30)->nullable();
            $table->decimal('velocidade', 15)->nullable()->default(0);
            $table->decimal('altitude', 15)->nullable()->default(0);
            $table->string('direcao', 20)->nullable();
            $table->string('mac', 12);
            $table->string('provedor', 30)->nullable();
            $table->string('precisao', 20)->nullable();
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();
            $table->boolean('sinc_erp')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('rastro');
    }
};
