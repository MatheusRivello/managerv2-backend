<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Tenant\MixProduto;
use Illuminate\Http\Request;

class MixProdutoMobileController extends BaseMobileController
{
    protected $className;

    public function __construct(Request $request)
    {
        $this->className = "mixproduto";
        $this->model = MixProduto::class;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    public function retornamixproduto()
    {

        $data = $this->model::join("vendedor_cliente", "mix_produto.id_cliente", "=", "vendedor_cliente.id_cliente")
        ->where("vendedor_cliente.id_vendedor", $this->usuarioLogado()->id_vendedor)
        ->get();

        $resposta = (is_null($data)) ? [
            'status' => 'erro',
            'code' => HTTP_NOT_FOUND,
            'mensagem' => 'Nenhum registro localizado com o MAC ' . $this->mac
        ] : [
            'status' => 'sucesso',
            'code' => HTTP_ACCEPTED,
            'data' => $data
        ];

        return response()->json($resposta, HTTP_CREATED);
    }
}
