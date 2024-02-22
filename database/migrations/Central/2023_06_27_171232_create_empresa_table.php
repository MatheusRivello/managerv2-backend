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
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_usuario_responsavel')->nullable()->index('fk_empresa_usuario1_idx');
            $table->string('razao_social', 60);
            $table->string('nome_fantasia', 60)->nullable();
            $table->string('codigo_autenticacao', 100)->nullable()->unique('cod_autenticacao');
            $table->string('cnpj', 14)->nullable();
            $table->string('email', 100)->nullable();
            $table->unsignedInteger('qtd_licenca')->nullable()->default(0);
            $table->unsignedInteger('qtd_licenca_utilizada')->nullable()->default(0);
            $table->string('contato', 50)->nullable();
            $table->string('telefone1', 11)->nullable();
            $table->string('telefone2', 11)->nullable();
            $table->text('observacao')->nullable();
            $table->boolean('usa_pw')->nullable()->default(false);
            $table->boolean('pw_status')->nullable()->default(false);
            $table->string('pw_dominio', 100)->nullable();
            $table->string('bd_host', 100)->nullable();
            $table->string('bd_porta', 10)->nullable();
            $table->string('bd_usuario', 100)->nullable();
            $table->string('bd_senha', 100)->nullable();
            $table->string('bd_nome', 100)->nullable();
            $table->boolean('bd_ssl')->nullable();
            $table->string('ip', 50)->nullable();
            $table->dateTime('dt_ultimo_login')->nullable();
            $table->boolean('status')->default(false);
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();
            $table->dateTime('dt_modificado')->nullable();
            $table->boolean('atualizar_sincronizador')->nullable()->default(false)->comment('0= Não atualiza,
1=Baixar e atualizar o sincronizador na empresa
2=Andamento
3=Erro');
            $table->string('versao_sincronizador', 45)->nullable()->default('0')->comment('Grava a verão do sincronizador que esta na empresa atualmente');
            $table->dateTime('dt_versao_sincronizador_atualizado')->nullable();
            $table->integer('pw_filial')->nullable();
            $table->string('pw_nome', 100)->nullable();
            $table->string('pw_logo', 100)->nullable();
            $table->mediumText('pw_termo')->nullable();
            $table->boolean('atualizada')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa');
    }
};
