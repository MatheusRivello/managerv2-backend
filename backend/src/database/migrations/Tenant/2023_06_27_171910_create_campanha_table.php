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
        Schema::connection('tenant')->create('campanha', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_filial')->nullable();
            $table->string('id_retaguarda', 15)->nullable();
            $table->string('descricao', 120)->nullable();
            $table->smallInteger('tipo_modalidade')->nullable();
            $table->date('data_inicial');
            $table->date('data_final');
            $table->boolean('permite_acumular_desconto')->default(false);
            $table->integer('mix_minimo')->nullable()->default(0);
            $table->decimal('valor_minimo', 15, 4)->nullable()->default(0);
            $table->decimal('valor_maximo', 15, 4)->nullable()->default(0);
            $table->decimal('volume_minimo', 15, 4)->nullable()->default(0);
            $table->decimal('volume_maximo', 15, 4)->nullable()->default(0);
            $table->integer('qtd_max_bonificacao')->nullable()->default(0);
            $table->tinyInteger('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('campanha');
    }
};
