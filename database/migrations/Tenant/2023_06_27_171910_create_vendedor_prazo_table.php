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
        Schema::connection('tenant')->create('vendedor_prazo', function (Blueprint $table) {
            $table->integer('id_filial')->index('fk_vendedor_prazo_filial_idx');
            $table->integer('id_prazo_pgto')->index('fk_vendedor_prazo_prazo_pgto_idx');
            $table->integer('id_vendedor')->index('fk_vendedor_prazo_vendedor_idx');

            $table->primary(['id_vendedor', 'id_prazo_pgto', 'id_filial']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('vendedor_prazo');
    }
};
