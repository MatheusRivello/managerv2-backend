<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\Visita;
use App\Services\BaseService;

class VisitaExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $verificationAndFilter = ['id', 'id_filial', 'id_motivo', 'id_vendedor', 'id_cliente', 'id_pedido_dispositivo', 'status', 'dt_cadastro', 'hora_inicio', 'hora_final'];
        $this->service = $service;
        $this->Entity = Visita::class;
        $this->filters = $verificationAndFilter;
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_VISITA_EXTERNA;
        $this->firstOrNew = $verificationAndFilter;
        $this->fields = [
            'id_filial',
            'id_motivo',
            'id_vendedor',
            'id_cliente',
            'id_pedido_dispositivo',
            'status',
            'sinc_erp',
            'dt_marcada',
            'hora_marcada',
            'observacao',
            'ordem',
            'latitude',
            'longitude',
            'precisao',
            'provedor',
            'lat_inicio',
            'lng_inicio',
            'lat_final',
            'lng_final',
            'precisao_inicio',
            'precisao_final',
            'hora_inicio',
            'hora_final',
            'dt_cadastro',
            'endereco_extenso_google'
        ];
    }
}
