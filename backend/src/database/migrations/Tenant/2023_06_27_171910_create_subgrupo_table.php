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
        Schema::connection('tenant')->create('subgrupo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_subgrupo_filial1_idx');
            $table->string('id_retaguarda', 15);
            $table->string('id_grupo', 15)->comment('Na versão v1 o id_grupo é o id_retaguarda da tabela grupo.
Na versão v2 o id_grupo é o id da tabela grupo');
            $table->string('subgrupo_desc', 60);
            $table->decimal('descto_max', 15)->nullable()->default(0);
            $table->boolean('status')->default(true);

            $table->unique(['id_retaguarda', 'id_grupo', 'id_filial'], 'ids_unicos_subgrupo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('subgrupo');
    }
};
