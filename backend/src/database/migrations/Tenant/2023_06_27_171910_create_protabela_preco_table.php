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
        Schema::connection('tenant')->create('protabela_preco', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_protabela_preco_filial1_idx');
            $table->string('id_retaguarda', 15);
            $table->string('tab_desc', 100);
            $table->date('tab_ini')->nullable();
            $table->date('tab_fim')->nullable();
            $table->boolean('gerar_verba')->nullable()->default(false);

            $table->unique(['id_retaguarda', 'id_filial'], 'ids_unicos_t_p');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('protabela_preco');
    }
};
