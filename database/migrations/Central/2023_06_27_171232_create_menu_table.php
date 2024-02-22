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
        Schema::create('menu', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('fk_tipo_empresa')->index('fk_menu_tipo_empresa1_idx');
            $table->integer('fk_menu')->nullable()->index('fk_menu_menu1_idx');
            $table->integer('fk_tipo_permissao')->nullable()->index('fk_menu_tipo_permissao1_idx');
            $table->string('classe', 100)->nullable();
            $table->string('descricao', 100);
            $table->text('url')->comment('url\'s dos menus do suporte tem o path \'a/\' no início');
            $table->boolean('personalizado')->nullable()->default(false)->comment('0=Não, 1=Sim');
            $table->text('extra')->nullable();
            $table->integer('ordem')->default(1);
            $table->boolean('exibir_cabecalho')->default(false)->comment('Caso deseje que apareça o menu no cabeçalho

1= Exibir, 0= Não exibir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
};
