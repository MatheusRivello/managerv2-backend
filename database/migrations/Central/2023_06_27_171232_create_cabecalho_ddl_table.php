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
        Schema::create('cabecalho_ddl', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('descricao', 100)->nullable();
            $table->text('codigo')->nullable()->comment('Código para a exibição no painel');
            $table->text('codigo_sem_tags')->nullable()->comment('Cógido para enviar para o sistema local');
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();
            $table->dateTime('dt_modificado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cabecalho_ddl');
    }
};
