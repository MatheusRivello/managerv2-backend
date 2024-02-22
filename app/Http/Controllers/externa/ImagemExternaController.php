<?php

namespace App\Http\Controllers\externa;

use App\Models\Tenant\ProdutoImagem;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class ImagemExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = ProdutoImagem::class;
        $this->tabela = 'produto_imagem';
        $this->modelComBarra = '\ProdutoImagem';
        $this->filters = ['id', 'id_produto', 'url', 'caminho'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_IMAGEM_EXTERNA;
        $this->firstOrNew = ['id_produto', 'url', 'caminho'];
        $this->fields = [
            'id_produto',
            'caminho',
            'padrao',
            'sequencia',
            'url',
            'dt_atualizacao'
        ];
    }

    public function storeProdutoImagem(Request $request)
    {

        try {

            $this->service->verificarCamposRequest($request, RULE_IMAGEM_EXTERNA, $request->id);

            $produtoImagem = ProdutoImagem::where(['id_produto' => $request->idProduto, 'sequencia' => $request->sequencia])->firstOrNew();

            if ($request->upload) {
                $this->service->deleteArquivo($produtoImagem->caminho);

                $upload = $this->service->salvarArquivo(
                    $request->upload,
                    TIPO_IMAGEM,
                    BD_TENANT,
                    [800, 800]
                );

                $pathImage = CAMINHO_PADRAO_STORAGE . $upload;
                $produtoImagem->caminho = $pathImage;
                $produtoImagem->id_produto = $request->idProduto;
                $produtoImagem->padrao = $request->padrao;
                $produtoImagem->sequencia = $request->sequencia;
                $produtoImagem->url = URL_BACKEND . DIRECTORY_SEPARATOR . "arquivos" . DIRECTORY_SEPARATOR . $upload;
                $produtoImagem->save();
            }


            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error:' => true, "message:" => $ex->getMessage()], $ex->getCode());
        }
    }

    public function destroyProdutoImagem($id)
    {
        try {

            if (isset($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }
           
            $registro = $this->Entity::find($id);

            if (!isset($registro)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            $this->service->deleteArquivo($registro->caminho);

            if ($registro->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }

        } catch (Exception $ex) {
            return response()->json(['error:' => true, "message:" => $ex->getMessage()], $ex->getCode());
        }
    }
}
