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
        Schema::connection('tenant')->create('rota', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('id_filial');
            $table->string('id_retaguarda');
            $table->string('descricao');
            $table->decimal('rota_frete', 15);
            $table->tinyInteger('rota_tipo_frete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('rota');
    }
};
