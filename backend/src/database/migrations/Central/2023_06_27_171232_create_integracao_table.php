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
        Schema::create('integracao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_empresa');
            $table->integer('integrador')->nullable()->comment('1=Vendas Externas
4=Magento 1.5');
            $table->string('url_base')->nullable();
            $table->string('url_loja')->nullable();
            $table->integer('id_filial')->nullable();
            $table->integer('id_tabela_preco')->nullable();
            $table->string('usuario')->nullable();
            $table->string('senha')->nullable();
            $table->string('campo_extra_1')->nullable();
            $table->string('campo_extra_2')->nullable();
            $table->string('campo_extra_3')->nullable();
            $table->string('campo_extra_4')->nullable();
            $table->string('campo_extra_5')->nullable();
            $table->dateTime('data_cadastro')->nullable();
            $table->dateTime('data_ativacao')->nullable();
            $table->dateTime('data_modificado');
            $table->boolean('status')->comment('0=Inativa\\n1=Ativa');
            $table->dateTime('execucao_inicio')->nullable()->useCurrent();
            $table->dateTime('execucao_fim')->nullable()->useCurrent();
            $table->integer('execucao_status')->default(-1)->comment('0=Em andamento, 1=Concluida');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integracao');
    }
};
