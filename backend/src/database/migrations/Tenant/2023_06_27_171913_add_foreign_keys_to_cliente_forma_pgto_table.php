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
        Schema::connection('tenant')->table('cliente_forma_pgto', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'fk_cliente_forma_pgto_cliente')->references(['id'])->on('cliente')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_forma_pgto'], 'fk_cliente_forma_pgto_forma_pagamento')->references(['id'])->on('forma_pagamento')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('cliente_forma_pgto', function (Blueprint $table) {
            $table->dropForeign('fk_cliente_forma_pgto_cliente');
            $table->dropForeign('fk_cliente_forma_pgto_forma_pagamento');
        });
    }
};
