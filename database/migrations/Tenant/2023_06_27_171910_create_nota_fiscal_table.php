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
        Schema::connection('tenant')->create('nota_fiscal', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_filial')->index('fk_nota_fiscal_filial_idx');
            $table->integer('ped_num')->index('fk_nota_fiscal_ped_num')->comment('Numero do pedido de venda referente a nota fiscal.');
            $table->integer('id_cliente')->index('fk_nota_fiscal_cliente1_idx');
            $table->integer('id_vendedor')->index('fk_nota_fiscal_vendedor1_idx');
            $table->string('nfs_doc', 10)->nullable()->index('fk_nota_fiscal_doc');
            $table->char('nfs_serie', 6)->nullable()->index('fk_nota_fiscal_serie');
            $table->integer('nfs_status')->comment('1=Totalmente Atendido
2=Parcialmente Atendido
3=Não Atendido
4=Devolução
5=Bonificação
6=Aguardando Faturamento');
            $table->dateTime('nfs_emissao')->nullable();
            $table->decimal('nfs_valbrut', 15)->nullable()->default(0)->comment('Campo referente ao valor total da nota fiscal');
            $table->char('nfs_tipo', 1)->comment('Tipo de documento:
0 = N.F. Normal
4 = N.F. Bonificação
D = N.F. Venda Plano - Faturamento
E = N.F. Venda Plano - Entrega Normal
F = N.F. Venda Plano - Bonificação');
            $table->dateTime('ped_entrega')->nullable()->comment('Previsão de entrega da nota fiscal
');
            $table->char('prazo_pag', 8)->nullable()->comment('Código do prazo de pagamento da nota fiscal.');
            $table->char('forma_pag', 8)->nullable();
            $table->dateTime('ped_emissao')->nullable()->comment('Data de emissao do pedido de venda referente a nota.');
            $table->decimal('ped_total', 15)->nullable()->default(0)->comment('Valor total do pedido de venda referente a nota.');
            $table->decimal('nfs_custo', 15)->nullable()->default(0);
            $table->string('observacao')->nullable();
            $table->binary('xml')->nullable();
            $table->decimal('nfs_peso', 15, 4)->nullable()->default(0);
            $table->string('chavenf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('nota_fiscal');
    }
};
