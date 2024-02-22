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
        Schema::create('configuracao_empresa', function (Blueprint $table) {
            $table->unsignedInteger('fk_empresa')->index('fk_empresa_sinc');
            $table->integer('fk_tipo_configuracao_empresa');
            $table->boolean('tipo')->comment('0=Integração Online
1=Integração programada');
            $table->boolean('valor')->comment('0=inativo, 1=ativo');
            $table->boolean('grupo')->nullable()->comment('0=Básico
1=Cliente
2=Produto
3=Vendedor
4=Método de pagamento');

            $table->unique(['fk_tipo_configuracao_empresa', 'tipo', 'fk_empresa'], 'ids_unicos_sincronizacao');
            $table->index(['fk_empresa'], 'fk_configuracao_empresa_empresa1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracao_empresa');
    }
};
