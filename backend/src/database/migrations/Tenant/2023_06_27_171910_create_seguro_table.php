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
        Schema::connection('tenant')->create('seguro', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('valor', 15, 6)->default(0);
            $table->char('uf', 2);
            $table->dateTime('dt_modificado')->default('1900-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('seguro');
    }
};
