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
        Schema::connection('tenant')->table('forma_prazo_pgto', function (Blueprint $table) {
            $table->foreign(['id_forma_pgto'], 'fk_forma_pagamento_has_prazo_pagamento_forma_pagamento1')->references(['id'])->on('forma_pagamento');
            $table->foreign(['id_prazo_pgto'], 'fk_forma_pagamento_has_prazo_pagamento_prazo_pagamento1')->references(['id'])->on('prazo_pagamento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->table('forma_prazo_pgto', function (Blueprint $table) {
            $table->dropForeign('fk_forma_pagamento_has_prazo_pagamento_forma_pagamento1');
            $table->dropForeign('fk_forma_pagamento_has_prazo_pagamento_prazo_pagamento1');
        });
    }
};
