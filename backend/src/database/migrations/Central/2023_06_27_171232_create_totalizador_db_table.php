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
        Schema::create('totalizador_db', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedInteger('fk_empresa')->index('fk_tamanho_db_empresa1_idx');
            $table->date('data');
            $table->decimal('tamanho_mb', 15)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('totalizador_db');
    }
};
