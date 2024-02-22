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
        Schema::create('corpo_ddl', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('id_empresa')->index('fk_corpo_dll_empresa1_idx');
            $table->integer('id_cabecalho_ddl')->index('fk_corpo_dll_cabecalho_dll1_idx');
            $table->text('codigo')->nullable()->comment('Cógido para exibição na tela do painel');
            $table->text('codigo_sem_tags')->nullable()->comment('Código para ser enviado para o sistema local');
            $table->boolean('status')->nullable()->default(false)->comment('0=Não baixar, 1=Baixar');
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();
            $table->dateTime('dt_modificado')->nullable();
            $table->dateTime('dt_injecao_ddl')->nullable()->comment('Quando foi feito a injeção da DDL no banco firebird');
            $table->dateTime('dt_status_modificado')->nullable()->comment('Quando foi feita a ultima modificação no status no painel, se marcou para baixar sim ou não.');
            $table->boolean('status_modificado_por_ultimo')->nullable()->comment('Qual foi o ultimo status a ser modificado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corpo_ddl');
    }
};
