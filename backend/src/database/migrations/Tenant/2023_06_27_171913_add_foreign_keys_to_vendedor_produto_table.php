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
        Schema::connection('tenant')->table('vendedor_produto', function (Blueprint $table) {
            $table->foreign(['id_produto'], 'id_produto_vp')->references(['id'])->on('produto')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_vendedor'], 'id_vendedor_vp')->references(['id'])->on('vendedor')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('vendedor_produto', function (Blueprint $table) {
            $table->dropForeign('id_produto_vp');
            $table->dropForeign('id_vendedor_vp');
        });
    }
};
