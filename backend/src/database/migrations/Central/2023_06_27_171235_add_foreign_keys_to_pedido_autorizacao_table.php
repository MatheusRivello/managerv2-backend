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
        Schema::table('pedido_autorizacao', function (Blueprint $table) {
            $table->foreign(['fk_empresa'], 'pedido_autorizacao_ibfk_1')->references(['id'])->on('empresa')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_usuario'], 'pedido_autorizacao_ibfk_2')->references(['id'])->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedido_autorizacao', function (Blueprint $table) {
            $table->dropForeign('pedido_autorizacao_ibfk_1');
            $table->dropForeign('pedido_autorizacao_ibfk_2');
        });
    }
};
