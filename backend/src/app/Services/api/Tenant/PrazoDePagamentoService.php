<?php

namespace App\Services\api\Tenant;

use App\Models\Tenant\PrazoPagamento;
use App\Services\BaseService;

class PrazoDePagamentoService extends BaseService
{
    public function index(){
        return PrazoPagamento::select(
            "id",
            "id_retaguarda as idRetaguarda",
            "descricao",
            "variacao",
            "valor_min as valorMin",
            "status"
        )->get();
    }
}