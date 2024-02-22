<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Tenant\ProdutoImagem;
use Exception;
use Illuminate\Http\Request;

class ProdutoImagemMobileController extends BaseMobileController
{
    protected $className;

    public function __construct()
    {
        $this->className = "Produtoimagem";
        $this->model = ProdutoImagem::class;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    public function produtoimagem()
    {
        $data = $this->getImagem();
        $resposta = (is_null($data)) ? [
            'status' => 'erro',
            'code' => HTTP_NOT_FOUND,
            'mensagem' => 'Nenhum registro localizado com o MAC ' . $this->mac
        ] : [
            'status' => 'sucesso',
            'code' => HTTP_ACCEPTED,
            'data' => $data
        ];

        return response()->json($resposta);
    }

    public function baixarImagem(Request $request)
    {
        try {
            $idProduto = $request->id;
            $sequencia = $request->seq;

            $imagem = $this->getImagemParaDownload($idProduto, $sequencia);

            if ($imagem != null) {
                $caminhoImagem = $imagem->caminho;
                $mime = mime_content_type($caminhoImagem); //<-- detect file type
                header('Content-Length: ' . filesize($caminhoImagem)); //<-- sends filesize header
                header("Content-Type: $mime"); //<-- send mime-type header
                header('Content-Disposition: inline; filename="' . $caminhoImagem . '";'); //<-- sends filename header
                readfile($caminhoImagem);
            } else {
                return response()->json(NULL, HTTP_NO_CONTENT);
            }
        } catch (Exception $ex) {
            return response()->json(["error" => true, "message" => $ex->getMessage(), "message_default" => FILE_NOT_FOUND], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getImagem()
    {
        $data = $this->model::select("id", "url", "id_produto", "sequencia", "padrao")->get();
        return $data;
    }

    private function getImagemParaDownload($id_produto, $sequencia)
    {
        $data = $this->model::select("id", "caminho")
            ->where([
                "id_produto" => $id_produto,
                "sequencia" => $sequencia
            ])
            ->first();

        return $data;
    }
}
