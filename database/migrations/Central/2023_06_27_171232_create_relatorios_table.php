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
        Schema::create('relatorios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_grupo')->nullable()->index('id_grupo');
            $table->string('image')->nullable();
            $table->string('titulo');
            $table->string('slug');
            $table->integer('tipo_grafico')->index('tipo_grafico');
            $table->boolean('status');
            $table->integer('user_cad')->index('user_cad');
            $table->dateTime('data_cad')->useCurrent();
            $table->integer('user_alt')->nullable()->index('user_alt');
            $table->dateTime('data_alt')->nullable();
            $table->string('query', 10000);
            $table->string('datakey', 600);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relatorios');
    }
};
