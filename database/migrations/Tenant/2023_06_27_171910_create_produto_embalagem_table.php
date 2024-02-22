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
        Schema::connection('tenant')->create('produto_embalagem', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('id_produto')->index('fk_produto_embalagem_produto_idx');
            $table->string('unidade', 100);
            $table->string('embalagem', 100)->nullable();
            $table->integer('fator')->default(0);
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('produto_embalagem');
    }
};
