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
        Schema::connection('tenant')->create('cliente_tabela_grupo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_cliente')->index('fk_cliente_tabela_grupo_cliente1_idx');
            $table->string('id_tabela', 15);
            $table->string('id_grupo', 15);

            $table->unique(['id_cliente', 'id_tabela', 'id_grupo'], 'ids_unicos_cliente_tabela_grupo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('cliente_tabela_grupo');
    }
};
