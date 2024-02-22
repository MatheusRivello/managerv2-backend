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
        Schema::create('log_dispositivo', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('mac', 12);
            $table->unsignedInteger('fk_empresa')->index('fk_empresa_log_dispositivo');
            $table->dateTime('data')->nullable()->comment('tipo*');
            $table->mediumText('descricao')->nullable();
            $table->text('contexto')->nullable();
            $table->string('codigoErro', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('versaoApp', 100)->nullable();
            $table->string('tipo', 100)->nullable();
            $table->string('ip', 30);
            $table->dateTime('dt_cadastro')->useCurrent();
            $table->boolean('resolvido')->nullable()->default(false);
            $table->dateTime('dt_resolvido')->nullable();

            $table->unique(['id', 'fk_empresa', 'mac'], 'ids_unicos_log_dispositivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_dispositivo');
    }
};
