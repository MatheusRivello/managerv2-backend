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
        Schema::connection('tenant')->create('forma_prazo_pgto', function (Blueprint $table) {
            $table->integer('id_forma_pgto')->index('fk_forma_pagamento_has_prazo_pagamento_forma_pagamento1_idx');
            $table->integer('id_prazo_pgto')->index('fk_forma_pagamento_has_prazo_pagamento_prazo_pagamento1_idx');

            $table->primary(['id_forma_pgto', 'id_prazo_pgto']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('forma_prazo_pgto');
    }
};
