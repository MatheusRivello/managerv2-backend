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
        Schema::connection('tenant')->create('visita', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_visita_filial1_idx');
            $table->integer('id_motivo')->nullable()->index('fk_visita_motivo1_idx');
            $table->integer('id_vendedor')->index('fk_visita_vendedor1_idx');
            $table->integer('id_cliente')->index('fk_visita_cliente1_idx');
            $table->integer('id_pedido_dispositivo')->nullable();
            $table->boolean('status')->default(false)->comment('0=Aberto, 1=Sem Visita, 2=Sem Venda, 3=Com Pedido');
            $table->boolean('sinc_erp');
            $table->date('dt_marcada')->nullable();
            $table->time('hora_marcada')->nullable();
            $table->string('observacao')->nullable();
            $table->integer('ordem')->nullable()->default(0);
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->string('precisao', 20)->nullable();
            $table->string('provedor', 20)->nullable();
            $table->string('lat_inicio', 20)->nullable();
            $table->string('lng_inicio', 20)->nullable();
            $table->string('lat_final', 20)->nullable();
            $table->string('lng_final', 20)->nullable();
            $table->string('precisao_inicio', 20)->nullable();
            $table->string('precisao_final', 20)->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_final')->nullable();
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();
            $table->string('endereco_extenso_google')->nullable()->comment('Quando foi salvo uma visita pelo aplicativo ele tentará salvar por extenço o endereço de onde foi gerado.');
            $table->tinyInteger('agendado_erp')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('visita');
    }
};
