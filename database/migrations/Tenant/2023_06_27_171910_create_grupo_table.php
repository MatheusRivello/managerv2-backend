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
        Schema::connection('tenant')->create('grupo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_grupo_filial1_idx');
            $table->string('id_retaguarda', 15);
            $table->string('grupo_desc', 60);
            $table->decimal('descto_max', 15)->nullable()->default(0);
            $table->boolean('status');

            $table->unique(['id_retaguarda', 'id_filial'], 'ids_unicos_grupo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('grupo');
    }
};
