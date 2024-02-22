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
        Schema::create('dispositivo', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fk_empresa')->index('fk_dispositivo_empresa1_idx');
            $table->string('marca', 80)->nullable();
            $table->string('mac', 12);
            $table->string('password', 100)->nullable();
            $table->string('modelo', 80)->nullable();
            $table->string('versaoApp', 45)->nullable();
            $table->string('versao_android', 20)->nullable();
            $table->string('imei', 20)->nullable();
            $table->boolean('licenca')->nullable()->default(false)->comment('1 = Não  Autenticado, 2 = Autenticado, 3 = Bloqueado');
            $table->text('id_unico')->nullable();
            $table->text('token_push')->nullable();
            $table->string('id_vendedor', 15);
            $table->boolean('status');
            $table->text('obs')->nullable();
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();
            $table->dateTime('dt_modificado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispositivo');
    }
};
