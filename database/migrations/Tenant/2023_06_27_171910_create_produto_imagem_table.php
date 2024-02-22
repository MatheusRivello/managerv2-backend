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
        Schema::connection('tenant')->create('produto_imagem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_produto')->index('fk_produto_imagem_produto1_idx');
            $table->text('caminho')->nullable()->comment('Url da imagem');
            $table->boolean('padrao')->nullable();
            $table->integer('sequencia')->nullable();
            $table->text('url')->nullable();
            $table->dateTime('dt_atualizacao')->useCurrent();

            $table->unique(['id_produto', 'sequencia'], 'ids_unicos_produto_imagem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('produto_imagem');
    }
};
