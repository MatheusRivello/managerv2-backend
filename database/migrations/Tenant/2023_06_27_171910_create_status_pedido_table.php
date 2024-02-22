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
        Schema::connection('tenant')->create('status_pedido', function (Blueprint $table) {
            $table->integer('id_filial');
            $table->string('id_pedido', 15)->comment('Id do pedido local');
            $table->date('data')->comment('Data da alteração do pedido local');
            $table->char('status', 1)->comment('D=Deletado');
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();

            $table->unique(['id_filial', 'id_pedido'], 'status_pedido_ids_unicos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('status_pedido');
    }
};
