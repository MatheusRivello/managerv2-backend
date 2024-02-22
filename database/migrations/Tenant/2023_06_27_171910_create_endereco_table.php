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
        Schema::connection('tenant')->create('endereco', function (Blueprint $table) {
            $table->string('id_retaguarda', 25)->primary()->comment('O id unico virá do sig');
            $table->integer('id_cliente')->index('fk_endereco_cliente');
            $table->boolean('tit_cod')->comment('Código do tipo de endereço campo no SIG = TIT_COD');
            $table->integer('id_cidade')->index('fk_endereco_cidade1_idx');
            $table->boolean('sinc_erp')->nullable()->default(false);
            $table->string('cep', 9)->nullable();
            $table->string('logradouro', 40)->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('complemento', 20)->nullable();
            $table->string('bairro', 20)->nullable();
            $table->char('uf', 2)->nullable();
            $table->string('latitude', 15)->nullable();
            $table->string('longitude', 15)->nullable();
            $table->string('referencia', 120)->nullable();
            $table->string('rota_cod', 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('endereco');
    }
};
