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
        Schema::create('horario', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('seg_i', 5)->nullable()->default('00:00');
            $table->string('seg_f', 5)->nullable()->default('00:00');
            $table->string('ter_i', 5)->nullable()->default('00:00');
            $table->string('ter_f', 5)->nullable()->default('00:00');
            $table->string('qua_i', 5)->nullable()->default('00:00');
            $table->string('qua_f', 5)->nullable()->default('00:00');
            $table->string('qui_i', 5)->nullable()->default('00:00');
            $table->string('qui_f', 5)->nullable()->default('00:00');
            $table->string('sex_i', 5)->nullable()->default('00:00');
            $table->string('sex_f', 5)->nullable()->default('00:00');
            $table->string('sab_i', 5)->nullable()->default('00:00');
            $table->string('sab_f', 5)->nullable()->default('00:00');
            $table->string('dom_i', 5)->nullable()->default('00:00');
            $table->string('dom_f', 5)->nullable()->default('00:00');
            $table->boolean('status_seg')->nullable()->default(true);
            $table->boolean('status_ter')->nullable()->default(true);
            $table->boolean('status_qua')->nullable()->default(true);
            $table->boolean('status_qui')->nullable()->default(true);
            $table->boolean('status_sex')->nullable()->default(true);
            $table->boolean('status_sab')->nullable()->default(true);
            $table->boolean('status_dom')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horario');
    }
};
