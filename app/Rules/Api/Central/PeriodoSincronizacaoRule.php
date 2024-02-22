<?php

namespace App\Rules\Api\Central;

class PeriodoSincronizacaoRule
{

    public function rules($id, $connection = null)
    {

        return [
            'fkEmpresa' => [
                'required',
                'int'
            ],
            'fkUsuario' => [
                'nullable',
                'int'
            ],
            'dia' => [
                'Required',
                'string',
                'max:150'
            ],
            'hora' => [
                'nullable',
                'text'
            ],
            'periodo' => [
                'nullable',
                'int'
            ],
            'registroLote' => [
                'nullable',
                'int'
            ],
            'qtdDiasNotaFiscal' => [
                'nullable',
                'int'
            ],
            'qtdDiasNotaFiscalApp' => [
                'nullable',
                'int'
            ],
            'restricaoProduto' => [
                'nullable',
                'int'
            ],
            'restricaoProtabelaPreco' => [
                'nullable',
                'int'
            ],
            'restricaoVendedorCliente' => [
                'nullable',
                'int'
            ],
            'restricaoSupervisorCliente' => [
                'nullable',
                'int'
            ],
            'dtCadastroOnline' => [
                'nullable',
                'date'
            ],
            'dtExecucaoOnline' => [
                'nullable',
                'date'
            ],
            'dtCadastroOnlineFim' => [
                'nullable',
                'date'
            ],
            'baixarOnline' => [
                'nullable',
                'int',
                'max:1'
            ],
            'tokenOnlineProcessando' => [
                'nullable',
                'string',
                'max:80'
            ]

        ];
    }
}
