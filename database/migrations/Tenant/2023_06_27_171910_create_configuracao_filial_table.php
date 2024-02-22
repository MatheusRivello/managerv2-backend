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
        Schema::connection('tenant')->create('configuracao_filial', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial');
            $table->string('descricao', 100);
            $table->text('valor');
            $table->string('tipo', 10);

            $table->unique(['id_filial', 'descricao'], 'fk_filial_UNIQUE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('configuracao_filial');
    }
};
