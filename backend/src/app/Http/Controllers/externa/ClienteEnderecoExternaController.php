<?php

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Endereco;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class ClienteEnderecoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Endereco::class;
        $this->filters = ['id_cliente', 'id_cidade', 'cep'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CLIENTE_ENDERECO_EXTERNA;
        $this->firstOrNew = ['id_retaguarda', 'id_cliente', 'tit_cod'];
        $this->fields = [
            'id_retaguarda',
            'id_cliente',
            'tit_cod',
            'id_cidade',
            'sinc_erp',
            'cep',
            'logradouro',
            'numero',
            'complemento',
            'bairro',
            'uf',
            'latitude',
            'longitude',
            'referencia'
        ];
    }

    public function destroyIdRetaguarda(Request $request)
    {
        return $this->destroy($request->id);
    }
}
