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
        Schema::connection('tenant')->create('configuracao_pedidoweb', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('descricao', 30)->nullable()->unique('configuracao_pedidoweb_descricao_uindex');
            $table->string('valor', 50)->nullable();
            $table->integer('tipo')->nullable()->default(0);
            $table->string('label', 100)->nullable();
            $table->string('valor_padrao', 200)->nullable()->comment('Exclusivamente utilizado para o Select. Deverá ser informado os valor separado por ( | ).Ex: (0-Não|1-Obrigatório|2-Opcional)');
            $table->string('campo', 45)->nullable()->comment('Informa como deverá ser o campo na tela para o usuário utilizar. Ex: select, input, checkbox, radio');
            $table->string('tamanho_maximo', 20)->nullable();
            $table->string('tabela_bd', 60)->nullable();
            $table->text('info_tabela')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('configuracao_pedidoweb');
    }
};
