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
        Schema::connection('tenant')->create('produto_cashback', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_integrador');
            $table->integer('id_produto')->index('id_produto');
            $table->decimal('cashback', 15)->default(0);
            $table->dateTime('dt_modificado')->useCurrent();

            $table->unique(['id_produto', 'id_integrador'], 'id_produto_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('produto_cashback');
    }
};
