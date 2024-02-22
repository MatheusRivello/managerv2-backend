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
        Schema::create('historico_ddl', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('tipo');
            $table->string('descricao_view', 100);
            $table->integer('fk_cabecalho_ddl')->nullable()->index('fk_historico_ddl_cabecalho_ddl1_idx');
            $table->integer('fk_corpo_ddl')->nullable()->index('fk_historico_ddl_corpo_ddl1_idx');
            $table->integer('fk_usuario')->index('fk_historico_ddl_usuario1_idx');
            $table->string('tabela', 30);
            $table->string('mensagem', 200);
            $table->text('conteudo')->nullable();
            $table->text('conteudo_anterior')->nullable();
            $table->dateTime('dt_cadastro')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historico_ddl');
    }
};
