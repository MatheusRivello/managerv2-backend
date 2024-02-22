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
        Schema::connection('tenant')->create('produto_estoque', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('id_produto')->index('fk_produto_estoque_produto_idx');
            $table->string('unidade', 100);
            $table->decimal('quantidade', 20, 5)->nullable();
            $table->dateTime('dt_modificado')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('produto_estoque');
    }
};
