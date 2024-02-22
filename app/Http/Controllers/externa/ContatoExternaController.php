<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Contato;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ContatoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Contato::class;
        $this->filters = ['id_cliente', 'nome', 'email'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_CONTATO_EXTERNA;
        $this->firstOrNew = ['id_cliente', 'con_cod', 'nome', 'email'];
        $this->fields = [
            'id_cliente',
            'con_cod',
            'telefone',
            'email',
            'nome',
            'aniversario',
            'hobby',
            'sinc_erp'
        ];
        $this->tabela = "Contato";
    }
    public function storePersonalizado(Request $request)
    {
        $request->idIgnorar = ["id_cliente" => $request->idCliente, "con_cod" => $request->conCod];

        return $this->store($request);
    }
    public function destroyPersonalizado(Request $request)
    {
        if (isset($request->idCliente)) {
            $where = ["id_cliente" => $request->idCliente, "con_cod" => $request->conCod];
        }

        return $this->destroyWhere($where);
    }
}
