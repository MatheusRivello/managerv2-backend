<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Tenant\ProdutoEstoque;
use Illuminate\Http\Request;

class ProdutoEstoqueMobileController extends BaseMobileController
{
    protected $className;

    public function __construct(Request $request)
    {
        $this->className = "Produtoestoque";
        $this->model = ProdutoEstoque::class;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    public function setProdutoEstoque(Request $request)
    {
        $data = $this->getAllEstoque(NULL, $request->dt_modificado);

        $resposta = (is_null($data)) ? [
            'status' => 'erro',
            'code' => HTTP_NOT_FOUND,
            'erros' =>  'Nenhum registro localizado'
        ] :
            [
                'status' => 'sucesso',
                'code' => HTTP_ACCEPTED,
                'data' => $data
            ];

        return response()->json($resposta, HTTP_CREATED);
    }

    public function produto(int $idProduto)
    {
        $data = NULL;

        $where = ['produto_estoque.id_produto' => $idProduto];
        $data = $this->getAllEstoque($where);

        $resposta = (is_null($data)) ? [
            'status' => 'erro',
            'code' => HTTP_NOT_FOUND
        ] :
            [
                'status' => 'sucesso',
                'code' => HTTP_ACCEPTED,
                'data' => $data
            ];

        return response()->json($resposta, $resposta['code']);
    }

    private function getAllEstoque($where = NULL, $dtModificado = NULL)
    {
        $data =  $this->model::select(
            "produto_estoque.id_produto",
            "produto_estoque.unidade",
            "produto_estoque.quantidade",
            "produto_estoque.dt_modificado"
        )
            ->join("produto", "produto.id", "=", "produto_estoque.id_produto");

        if (isset($dtModificado)) {
            $data->where("dt_modificado", ">=", $dtModificado);
        }

        if (isset($where)) {
            $data->where($where);
        }

        return $data->get();
    }
}
