<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\VendedorProtabelapreco;
use App\Services\BaseService;
use Illuminate\Http\Request;

class VendedorTabelaPrecoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = VendedorProtabelapreco::class;
        $this->tabela='vendedor_protabelapreco';
        $this->modelComBarra='\VendedorProtabelapreco';
        $this->filters = ['id_protabela_preco', 'id_vendedor'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath =RULE_VENDEDOR_X_TABELA_PRECO;
        $this->firstOrNew =  ['id_protabela_preco', 'id_vendedor'];
        $this->fields = [
            'id_protabela_preco', 
            'id_vendedor'
        ];
    }

    public function destroyPersonalizado(?Request $request,$where=null)
    {   
        if(isset($request)){
            $where = ['id_protabela_preco' => $request->idProtabelaPreco, 'id_vendedor' => $request->idVendedor];
        }
       return $this->destroyWhere($where);
    }

    public function StoreVendedorCliente(Request $request){
       
        $where = ['id_protabela_preco' => $request->idProtabelaPreco, 'id_vendedor' => $request->idVendedor];
        $this->destroyPersonalizado(null,$where);
        return  $this->storePersonalizado($request);  
    }
}