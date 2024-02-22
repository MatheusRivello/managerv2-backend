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
        Schema::create('perfil_menu', function (Blueprint $table) {
            $table->integer('fk_perfil')->index('fk_table1_perfil1_idx');
            $table->integer('fk_menu')->index('fk_perfil_menu_menu1_idx');

            $table->primary(['fk_perfil', 'fk_menu']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfil_menu');
    }
};
