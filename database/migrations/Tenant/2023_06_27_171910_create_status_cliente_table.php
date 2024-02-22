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
        Schema::connection('tenant')->create('status_cliente', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('id_retaguarda', 15)->unique('id_retaguarda_UNIQUE');
            $table->string('descricao', 60);
            $table->boolean('status')->default(true);
            $table->boolean('bloqueia')->default(false)->comment('0=Não bloqueia, 1=Bloqueia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('status_cliente');
    }
};
