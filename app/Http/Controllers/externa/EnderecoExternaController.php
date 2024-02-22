<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Endereco;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class EnderecoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Endereco::class;
        $this->filters = ['id_retaguarda', 'id_cliente', 'cep', 'id_cidade'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_ENDERECO_EXTERNA;
        $this->firstOrNew = ['id_cliente', 'tit_cod', 'id_retaguarda', 'id_cidade'];
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
        $this->tabela='endereco';
        $this->modelComBarra='\endereco';
    }

    public function destroyPersonalizado(Request $request)
    {
        try {
            if (isset($request->idCliente)) {
                $this->where = ["id_cliente" => $request->idCliente, 'id_retaguarda' => $request->idRetaguarda,'tit_cod'=>$request->titCod,"id_cidade"=>$request->idCidade];
            }
            if (!isset($this->where)) {
                throw new Exception(ERRO_VARIAVEL_INDEFINIDA, 400);
            }
            return $this->destroyWhere($this->where);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 406);
        }
    }
}
