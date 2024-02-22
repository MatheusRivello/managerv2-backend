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
        Schema::create('aviso', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('fk_usuario')->index('fk_aviso_usuario1_idx');
            $table->string('titulo', 100);
            $table->text('descricao')->nullable();
            $table->text('caminho_imagem')->nullable();
            $table->text('url_imagem')->nullable();
            $table->text('url_imagem_thumb')->nullable();
            $table->boolean('status')->comment('Rascunho=0, Ativo=1, Finalizado =2,  Excluido=4');
            $table->timestamp('dt_inicio')->nullable();
            $table->timestamp('dt_fim')->nullable();
            $table->timestamp('dt_cadastro')->nullable()->useCurrent();
            $table->timestamp('dt_modificado')->nullable();
            $table->boolean('obrigatorio')->default(false)->comment('Não= 0, Sim = 1');
            $table->boolean('exibir_titulo')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aviso');
    }
};
