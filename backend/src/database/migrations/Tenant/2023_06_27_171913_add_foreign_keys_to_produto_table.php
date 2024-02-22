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
        Schema::connection('tenant')->table('produto', function (Blueprint $table) {
            $table->foreign(['id_filial'], 'fk_produto_filial')->references(['id'])->on('filial')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_grupo_new'], 'fk_produto_grupo_new')->references(['id'])->on('grupo')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_fornecedor'], 'fk_produto_fornecedor1')->references(['id'])->on('fornecedor')->onUpdate('CASCADE');
            $table->foreign(['id_subgrupo_new'], 'fk_produto_subgrupo_new')->references(['id'])->on('subgrupo')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('produto', function (Blueprint $table) {
            $table->dropForeign('fk_produto_filial');
            $table->dropForeign('fk_produto_grupo_new');
            $table->dropForeign('fk_produto_fornecedor1');
            $table->dropForeign('fk_produto_subgrupo_new');
        });
    }
};
