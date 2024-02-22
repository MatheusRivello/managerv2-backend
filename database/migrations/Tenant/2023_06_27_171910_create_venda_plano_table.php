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
        Schema::connection('tenant')->create('venda_plano', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('id_filial')->index('fk_venda_plano_filial_idx');
            $table->integer('id_cliente')->index('fk_venda_plano_cliente_idx');
            $table->string('nfs_num', 16);
            $table->string('nfs_serie', 20)->nullable();
            $table->string('nfs_doc', 16);
            $table->date('nfs_emissao')->nullable();
            $table->string('tipo_saida', 16);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('venda_plano');
    }
};
