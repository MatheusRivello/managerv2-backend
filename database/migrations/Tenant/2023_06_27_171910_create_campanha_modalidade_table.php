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
        Schema::connection('tenant')->create('campanha_modalidade', function (Blueprint $table) {
            $table->unsignedInteger('id_campanha');
            $table->string('id_retaguarda', 20);

            $table->primary(['id_campanha', 'id_retaguarda']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('campanha_modalidade');
    }
};
