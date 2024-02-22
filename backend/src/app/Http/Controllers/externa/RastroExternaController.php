<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Rastro;
use App\Services\BaseService;


class RastroExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Rastro::class;
        $this->filters = ['id', 'id_vendedor', 'sinc_erp'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_RASTRO_EXTERNA;
        $this->firstOrNew = ['id', 'id_vendedor','data','hora'];
        $this->fields = [
            'id_vendedor',
            'data',
            'hora',
            'latitude',
            'longitude',
            'velocidade',
            'altitude',
            'direcao',
            'mac',
            'provedor',
            'precisao',
            'dt_cadastro',
            'sinc_erp'
        ];
    }
}
