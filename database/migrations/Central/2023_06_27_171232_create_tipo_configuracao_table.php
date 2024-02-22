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
        Schema::create('tipo_configuracao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label', 60)->nullable();
            $table->string('descricao', 60);
            $table->string('tipo', 10);
            $table->string('campo', 45)->nullable()->comment('Informa como deverá ser o campo na tela para o usuário utilizar. 
Ex: select, input, checkbox, radio');
            $table->string('tamanho_maximo', 20)->nullable();
            $table->tinyInteger('ordem');
            $table->string('valor_padrao', 200)->nullable()->comment('Exclusivamente utilizado para o Select. Deverá ser informado os valor separado por ( | ).Ex: (0-Não|1-Obrigatório|2-Opcional)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_configuracao');
    }
};
