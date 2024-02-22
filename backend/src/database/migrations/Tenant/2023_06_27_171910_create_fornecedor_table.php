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
        Schema::connection('tenant')->create('fornecedor', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_fornecedor_filial1_idx');
            $table->string('id_retaguarda', 15);
            $table->string('razao_social', 60)->nullable();
            $table->string('nome_fantasia', 60)->nullable();
            $table->boolean('status')->default(true)->comment('0=Inativo, 1=Ativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('fornecedor');
    }
};
