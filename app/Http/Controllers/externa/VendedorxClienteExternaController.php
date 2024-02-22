<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\VendedorCliente;
use App\Services\BaseService;
use Illuminate\Http\Request;

class VendedorxClienteExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = VendedorCliente::class;
        $this->tabela='vendedor_cliente';
        $this->modelComBarra='\VendedorCliente';
        $this->filters = ['id_cliente', 'id_vendedor'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_VENDEDOR_X_CLIENTE_EXTERNA;
        $this->firstOrNew = ['id_cliente', 'id_vendedor'];
        $this->fields = [
            'id_cliente',
            'id_vendedor'
        ];
    }

    public function destroyPersonalizado(?Request $request,$where=null)
    {   
        if(isset($request)){
            $where = ['id_cliente' => $request->idCliente, 'id_vendedor' => $request->idVendedor];
            return $this->destroyWhere($where);
        }
       return $this->destroyWhere($where);
    }

    public function StoreVendedorCliente(Request $request){
        $where = ['id_cliente' => $request->idCliente, 'id_vendedor' => $request->idVendedor];
        $this->destroyPersonalizado(null,$where);
        return  $this->storePersonalizado($request);  
    }
}
