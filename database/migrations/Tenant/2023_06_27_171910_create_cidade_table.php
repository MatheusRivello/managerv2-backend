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
        Schema::connection('tenant')->create('cidade', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->char('uf', 2);
            $table->string('descricao', 100)->nullable();
            $table->string('codigo_ibge', 45)->nullable();
            $table->char('ddd', 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('cidade');
    }
};
