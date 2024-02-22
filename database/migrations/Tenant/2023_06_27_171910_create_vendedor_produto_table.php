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
        Schema::connection('tenant')->create('vendedor_produto', function (Blueprint $table) {
            $table->integer('id_produto')->index('id_produto_vf_idx');
            $table->integer('id_vendedor')->index('id_vendedor_vf_idx');

            $table->primary(['id_produto', 'id_vendedor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('vendedor_produto');
    }
};
