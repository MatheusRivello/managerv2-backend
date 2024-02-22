<?php

namespace App\Rules\Api\Externa;

class ProdutoImagemExternaRule
{
    public function rules($id,$conection)
    {
        return [
            'idProduto' => [
                'Required',
                "exists:{$conection}.produto,id",
                'int'
            ],
            'padrao' => [
                'Required',
                'int'
            ],
            'sequencia' => [
                'Required',
                'int'
            ],
            "upload" => [
                'required',
                'mimes:jpg,jpeg,bmp,png',
                'file',
                'max:3072'
            ],
        ];
    }
}
