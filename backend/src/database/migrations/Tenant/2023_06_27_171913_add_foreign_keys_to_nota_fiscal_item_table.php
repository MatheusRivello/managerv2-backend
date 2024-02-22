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
        Schema::connection('tenant')->table('nota_fiscal_item', function (Blueprint $table) {
            $table->foreign(['ped_num'], 'fk_nota_fical_item_ped_num')->references(['ped_num'])->on('nota_fiscal')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['nfs_doc'], 'fk_nota_fiscal_item_doc')->references(['nfs_doc'])->on('nota_fiscal')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['nfs_serie'], 'fk_nota_fiscal_item_serie')->references(['nfs_serie'])->on('nota_fiscal')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_filial'], 'fk_nota_fiscal_filial')->references(['id_filial'])->on('nota_fiscal')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_produto'], 'fk_nota_fiscal_item_produto')->references(['id'])->on('produto')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('nota_fiscal_item', function (Blueprint $table) {
            $table->dropForeign('fk_nota_fical_item_ped_num');
            $table->dropForeign('fk_nota_fiscal_item_doc');
            $table->dropForeign('fk_nota_fiscal_item_serie');
            $table->dropForeign('fk_nota_fiscal_filial');
            $table->dropForeign('fk_nota_fiscal_item_produto');
        });
    }
};
