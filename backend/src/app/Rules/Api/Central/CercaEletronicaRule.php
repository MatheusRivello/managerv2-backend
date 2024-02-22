<?php 

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class CercaEletronicaRule
{

    public function rules($id, $connection = null)
    {
       return [
           "id_motivo" => [
            'required',
            'integer',
            'exists:motivo_cerca_eletronica,id'
        ],
        "id_vendedor" => [
            'required',
            'integer',
            "exists:{$connection}.vendedor,id"
        ]
    ];

}

}

?>