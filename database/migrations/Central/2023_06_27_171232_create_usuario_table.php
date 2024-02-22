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
        Schema::create('usuario', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('fk_empresa')->nullable()->index('fk_empresa_usu_idx');
            $table->integer('fk_perfil')->nullable()->index('fk_usuario_perfil1_idx');
            $table->integer('fk_tipo_empresa')->index('fk_usuario_tipo_empresa1_idx');
            $table->string('id_gerente_supervisor', 15)->nullable()->comment('Informar os respectivos Ids caso for gerente ou supervisor');
            $table->string('nome', 100);
            $table->string('telefone', 11)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('usuario', 30);
            $table->string('senha', 30);
            $table->string('password', 100)->nullable();
            $table->string('codigo_autenticacao', 100)->nullable();
            $table->boolean('tipo_acesso')->default(false)->comment('100 = "ACESSO SIG200"');
            $table->boolean('status')->nullable()->default(false)->comment('0=Inativo, 1=Ativo,2=Excluido');
            $table->boolean('responsavel')->nullable()->default(false);
            $table->dateTime('codigo_tempo')->nullable();
            $table->string('codigo_senha', 40)->nullable();
            $table->dateTime('dt_cadastro')->nullable()->useCurrent();
            $table->dateTime('dt_modificado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
};
