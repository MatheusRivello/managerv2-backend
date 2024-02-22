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
        Schema::table('perfil_menu', function (Blueprint $table) {
            $table->foreign(['fk_menu'], 'fk_perfil_menu_menu1')->references(['id'])->on('menu')->onUpdate('CASCADE');
            $table->foreign(['fk_perfil'], 'fk_table1_perfil1')->references(['id'])->on('perfil')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perfil_menu', function (Blueprint $table) {
            $table->dropForeign('fk_perfil_menu_menu1');
            $table->dropForeign('fk_table1_perfil1');
        });
    }
};
