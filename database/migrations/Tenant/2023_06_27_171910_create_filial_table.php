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
        Schema::connection('tenant')->create('filial', function (Blueprint $table) {
            $table->integer('id')->primary()->comment('A informação deste campo pega direto do campo EMP_COD do SIG');
            $table->string('emp_cgc', 18)->nullable();
            $table->string('emp_raz', 60)->nullable();
            $table->string('emp_fan', 60)->nullable();
            $table->boolean('emp_ativa');
            $table->char('emp_uf', 2)->nullable();
            $table->text('emp_caminho_img')->nullable();
            $table->text('emp_url_img')->nullable();
            $table->string('emp_email', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('filial');
    }
};
