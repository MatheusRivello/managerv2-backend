<?php

namespace App\Services\api\Tenant;

use App\Models\Tenant\ProtabelaPreco;
use App\Services\BaseService;

class TabelaPrecoService extends BaseService
{
    public function index(){
        return ProtabelaPreco::select(
            "id",
            "id_filial as idFilial",
            "id_retaguarda as idRetaguarda",
            "tab_desc as tabDesc",
            "tab_ini as tabIni",
            "tab_fim as tabFim",
            "gerar_verba as gerarVerba"
        )->get();
    }
}