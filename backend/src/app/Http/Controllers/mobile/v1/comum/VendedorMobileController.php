<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use Illuminate\Http\Request;
use App\Models\Tenant\Vendedor;

class VendedorMobileController extends BaseMobileController
{
    protected $className;

    public function __construct(Request $request)
    {
        $this->className = "vendedor";
        $this->model = Vendedor::class;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    /**
     *RETORNA OS DADOS DO VENDEDOR
     */
    public function vendedor()
    {
        // $data = $this->Vendedor_model->get_vendedor($this->bdEmpresa->id_vendedor);
        $data = $this->model::find($this->usuarioLogado()->vendedor->id);
        $resposta = (is_null($data)) ? [
            'status' => 'erro',
            'code' => HTTP_NOT_FOUND,
            'mensagem' => 'Nenhum registro localizado com o MAC ' . $this->mac
        ] : [
            'status' => 'sucesso',
            'code' => HTTP_ACCEPTED,
            'data' => [
                $data
            ]
        ];

        return response()->json($resposta, HTTP_CREATED);

    }
}
