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
        Schema::table('menu', function (Blueprint $table) {
            $table->foreign(['fk_menu'], 'fk_menu_menu1')->references(['id'])->on('menu')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['fk_tipo_permissao'], 'fk_menu_tipo_permissao1')->references(['id'])->on('tipo_permissao')->onUpdate('CASCADE');
            $table->foreign(['fk_tipo_empresa'], 'fk_menu_tipo_empresa1')->references(['id'])->on('tipo_empresa')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->dropForeign('fk_menu_menu1');
            $table->dropForeign('fk_menu_tipo_permissao1');
            $table->dropForeign('fk_menu_tipo_empresa1');
        });
    }
};
