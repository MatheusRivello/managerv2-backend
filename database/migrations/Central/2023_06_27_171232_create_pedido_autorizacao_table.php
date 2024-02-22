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
        Schema::create('pedido_autorizacao', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fk_empresa')->index('fk_ped_autoriza_empresa_idx');
            $table->integer('fk_usuario')->index('fk_ped_autoriza_usuario_idx');
            $table->integer('id_pedido');
            $table->boolean('liberado');
            $table->string('observacao', 100)->nullable()->default('NULL');
            $table->string('ip', 100);
            $table->dateTime('dt_cadastro')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_autorizacao');
    }
};
