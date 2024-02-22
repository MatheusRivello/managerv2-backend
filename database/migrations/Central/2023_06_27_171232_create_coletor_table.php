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
        Schema::create('coletor', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('fk_empresa');
            $table->string('identificador_unico', 120)->nullable();
            $table->string('fcm_token', 120)->nullable();
            $table->string('token', 45)->nullable();
            $table->string('modelo', 120)->nullable();
            $table->string('versaoAndroid', 10)->nullable();
            $table->timestamp('dt_ultima_sincronismo')->nullable();
            $table->boolean('status')->default(false);
            $table->longText('teste');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coletor');
    }
};
