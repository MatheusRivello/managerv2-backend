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
        Schema::connection('tenant')->create('produto', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_produto_filial_idx');
            $table->integer('id_fornecedor')->nullable()->index('fk_produto_fornecedor1_idx');
            $table->string('id_retaguarda', 20)->nullable();
            $table->string('id_grupo', 15);
            $table->string('id_subgrupo', 15)->nullable();
            $table->integer('id_grupo_new')->nullable()->index('fk_produto_grupo_new_idx');
            $table->integer('id_subgrupo_new')->nullable()->index('fk_produto_subgrupo_new_idx');
            $table->string('descricao', 100);
            $table->string('cod_barras', 20)->nullable();
            $table->string('dun', 20)->nullable();
            $table->string('ncm', 10)->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('descricao_curta')->nullable();
            $table->boolean('frete_gratis')->nullable()->default(false);
            $table->integer('status');
            $table->dateTime('pro_inicio')->nullable()->comment('Data de inicio da promoção do produto');
            $table->dateTime('pro_fim')->nullable()->comment('Data final da promoção do produto');
            $table->decimal('pro_unitario', 15, 3)->nullable()->default(0)->comment('preço do produto na promoção');
            $table->string('unidvenda', 8)->nullable();
            $table->string('embalagem', 10)->nullable();
            $table->integer('qtd_embalagem')->default(0)->comment('Quantidade por embalagem');
            $table->decimal('pro_qtd_estoque', 15, 3)->nullable()->default(0)->comment('Quantidade em estoque do produto, no SIG ja enviamos a quantidade disponivel do produto para venda');
            $table->decimal('pes_bru', 15, 3)->nullable()->default(0);
            $table->decimal('pes_liq', 15, 3)->nullable()->default(0);
            $table->decimal('comprimento', 15, 3)->default(0);
            $table->decimal('largura', 15, 3)->default(0);
            $table->decimal('espessura', 15, 3)->default(0);
            $table->char('ult_origem', 1)->nullable()->comment('Origem da ultima compra ( Utilizado para identificar o MVA aplicado no calculo da ST  )');
            $table->char('ult_uf', 2)->nullable()->comment('estado (UF) da ultima compra ( Utilizado para identificar o MVA aplicado no calculo da ST  )');
            $table->decimal('custo', 15, 3)->nullable()->default(0);
            $table->decimal('descto_verba', 15, 3)->nullable()->default(0);
            $table->text('aplicacao')->nullable();
            $table->string('referencia', 20)->nullable();
            $table->boolean('tipo_estoque')->nullable()->default(false);
            $table->date('dt_validade')->nullable();
            $table->decimal('multiplo', 14, 4)->nullable()->default(0);
            $table->boolean('integra_web')->nullable()->default(true);
            $table->dateTime('dt_alteracao')->nullable();
            $table->smallInteger('pw_filial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('produto');
    }
};
