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
        Schema::connection('tenant')->create('motivo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_motivo_filial1_idx');
            $table->string('id_retaguarda', 15)->nullable()->comment('Este campo pega informação do campo MOT_COD da tabela VENMOTIVOSNV');
            $table->text('descricao');
            $table->boolean('tipo')->nullable();
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('motivo');
    }
};
