<?php

namespace App\Rules\Api\Tenant;

use Illuminate\Validation\Rule;

class PedidoTenantRule
{
    public function rules($id, $connection = null)
    {
        return [
            "dtInicio" => [
                'required',
                'date'
            ],
            "dtFim" => [
                'required',
                'date'
            ],
            "tipo" => [
                'nullable',
                Rule::in('cidade', 'estado')
            ],
            "origem" => [
                'nullable',
                Rule::in('P', 'W')
            ],
            "status" => [
                'nullable',
                'array'
            ],
            "filial" => [
                'nullable',
                "exists:{$connection}.filial,id"
            ],
            "vendedor" => [
                'nullable',
                "exists:{$connection}.vendedor,id"
            ],
            "supervisor" => [
                'nullable',
                "exists:{$connection}.vendedor,id"
            ],
            "tipoPedido" => [
                'nullable',
                "exists:{$connection}.tipo_pedido,id"
            ],
        ];
    }
}
