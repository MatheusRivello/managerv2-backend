<?php

namespace App\Services\Util;

use App\Services\BaseService;
use App\Models\Tenant\ProdutoImagem;
use App\Models\Tenant\Produto;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Http\UploadedFile;

class ImagemService
{
    use \App\Services\Integracao\Traits\Log;

    private $PASTA_UPLOAD_EMPRESA;
    private $URL_ARQUIVO_ONLINE;
    private $service;
    private $erp_service;
    private $idEmpresa;
    private $caminho;
    private $caminhoTemp;
    private $caminhoDefault;
    private $nomeCamposDb;
    private $tabela;

    function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PRODUTO_IMAGEM;
        $this->idEmpresa = $this->service->usuarioLogado()->fk_empresa;
        $this->caminho = "emp-" . $this->idEmpresa;
        $this->caminhoTemp = $this->caminho . "temp" . DIRECTORY_SEPARATOR;
        $this->caminhoDefault = CAMINHO_PADRAO_STORAGE . $this->caminho;
        $this->erp_service = new ErpService(
            $this->service->usuarioLogado(),
            $this->caminho,
            $this->caminhoTemp,
            $this->caminhoDefault,
            $this->service->connectionTenant($this->idEmpresa),
            $this->nomeCamposDb,
            $this->tabela
        );
        $this->PASTA_UPLOAD_EMPRESA = $this->caminhoDefault;
        $this->URL_ARQUIVO_ONLINE = "emp-" . $this->idEmpresa . DIRECTORY_SEPARATOR;
    }

    public function upload($request)
    {
        $this->_verifica_pasta_empresa();
        $id_produto_interno = $this->_retorna_id_produto_interno($request->id_retaguarda , $request->id_filial);

        if (isset($id_produto_interno['status']) && $id_produto_interno['status'] = 'error') {
            return;
        }

        $sequencia = is_null($request->sequencia) ? 0 : $request->sequencia;
        $id_produto_md5 = md5($id_produto_interno);
        $nomeArquivo = md5($request->id_filial . $id_produto_interno . $request->sequencia);

        $pasta_produto = $this->URL_ARQUIVO_ONLINE . "produtos" . DIRECTORY_SEPARATOR . $id_produto_md5 . DIRECTORY_SEPARATOR;

        $url_imagem = URL_BACKEND . DIRECTORY_SEPARATOR . "arquivos/" . $this->URL_ARQUIVO_ONLINE . "produtos" . DIRECTORY_SEPARATOR . $id_produto_md5 . DIRECTORY_SEPARATOR;

        $this->_verifica_pasta_produto($id_produto_md5);

        $upload = $this->_salvarImagem($pasta_produto, $request, $nomeArquivo);

        if ($upload['status'] == "error") {
            $response = [
                'status' => 'erro',
                'code' => HTTP_NOT_FOUND,
                'mensagem' => $upload['descricao'],
                'data_img' =>  $upload['caminho']
            ];
        } else {
            $this->_salvar_foto_produto($nomeArquivo, $id_produto_interno, $sequencia, $pasta_produto, TRUE, $url_imagem, $upload['extensao']);
            $response = ['status' => 'sucesso', 'code' => HTTP_ACCEPTED];
        }

        return $response;
    }

    private function _salvar_foto_produto($nome_arquivo, $id_produto, $sequencia, $caminho_pasta = NULL, $principal = FALSE, $url_imagem, $extensao)
    {
        if ($principal) {
            $caminho_foto = ProdutoImagem::select("id", "caminho")
                ->where([
                    "id_produto" => $id_produto,
                    "sequencia" => $sequencia
                ])
                ->get();

            if ($caminho_foto != NULL) {
                //deleta a foto
                @unlink($caminho_foto); // com (@) ele não mostra se caso der erro
            }

            $imagemProduto = ProdutoImagem::firstOrNew([
                'id_produto' => $id_produto,
                'sequencia' => $sequencia
            ]);

            $imagemProduto->id_produto = $id_produto;
            $imagemProduto->sequencia = $sequencia;
            $imagemProduto->caminho = CAMINHO_PADRAO_STORAGE . $caminho_pasta . $nome_arquivo . '.' . $extensao;
            $imagemProduto->padrao = '1';
            $imagemProduto->url = $url_imagem . $nome_arquivo . '.' . $extensao;
            $imagemProduto->save();
        }
    }

    private function _verifica_pasta_produto($id_produto)
    {
        $caminho = $this->PASTA_UPLOAD_EMPRESA . DIRECTORY_SEPARATOR . "produtos" . DIRECTORY_SEPARATOR . $id_produto;
        if (!file_exists($caminho)) {
            $this->_criar_pasta($caminho);
        }
    }

    private function _retorna_id_produto_interno($id_produto_retaguarda, $id_filial)
    {
        $retorno = "";

        //Retorna o Id interno do produto, pelo id retaguarda do produto, e o Id da filial.
        $produto = Produto::select('id')->where(
            [
                ['id_retaguarda', $id_produto_retaguarda],
                ['id_filial', $id_filial]
            ]
        )->first();

        if ($produto !== NULL) {
            return intval($produto->id);
        } else {
            $retorno = [
                'status' => 'error',
                'code' => HTTP_NOT_FOUND,
                'mensagem' => "Não existe nenhum produto com o ID = " . $id_produto_retaguarda
            ];
        }

        return $retorno;
    }

    private function _verifica_pasta_empresa()
    {
        if (!file_exists($this->PASTA_UPLOAD_EMPRESA)) {
            $this->_criar_pasta($this->PASTA_UPLOAD_EMPRESA);
            $this->_criar_pasta($this->PASTA_UPLOAD_EMPRESA . DIRECTORY_SEPARATOR . 'produtos');
            $this->_criar_pasta($this->PASTA_UPLOAD_EMPRESA . DIRECTORY_SEPARATOR . 'marcas');
            $this->_criar_pasta($this->PASTA_UPLOAD_EMPRESA . DIRECTORY_SEPARATOR . 'outros');
        }

        if (file_exists($this->PASTA_UPLOAD_EMPRESA) && !file_exists($this->PASTA_UPLOAD_EMPRESA . DIRECTORY_SEPARATOR . 'produtos')) {
            $this->_criar_pasta($this->PASTA_UPLOAD_EMPRESA . DIRECTORY_SEPARATOR . 'produtos');
            $this->_criar_pasta($this->PASTA_UPLOAD_EMPRESA . DIRECTORY_SEPARATOR . 'marcas');
            $this->_criar_pasta($this->PASTA_UPLOAD_EMPRESA . DIRECTORY_SEPARATOR . 'outros');
        }
    }

    protected function _salvarImagem($caminho, $request, $nomeArquivo)
    {
        if (!isset($request->arquivo)) {
            $resposta = [
                'status' => 'erro',
                'descricao' => "Houve um imprevisto ao salvar a imagem na pasta",
                'caminho' => $caminho
            ];
        } else {
            // Recupera a extensão do arquivo

            $fileData = base64_decode($request->arquivo);
            $tmpFilePath = sys_get_temp_dir() . '/' . \Illuminate\Support\Str::uuid()->toString();

            file_put_contents($tmpFilePath, $fileData);

            $tmpFile = new File($tmpFilePath);

            $file = new UploadedFile(
                $tmpFile->getPathname(),
                $tmpFile->getFilename(),
                $tmpFile->getMimeType(),
                0,
                true
            );

            $upload = $this->service->salvarArquivo($file, TIPO_SERVICO_ZIP, BD_TENANT, [800, 800], $caminho, $nomeArquivo);

            $extensao = $file->getClientOriginalExtension();
            $extensao = empty($extensao) ? "jpg" : $extensao;

            $resposta = [
                'status' => 'sucesso',
                'caminho' => $upload,
                'extensao' => $extensao
            ];
        }

        return $resposta;
    }

    protected function _criar_pasta($caminho)
    {
        try
        {
            $this->erp_service->criarPasta($caminho);
        }
        catch (\Throwable $th)
        {
            // lança o erro com o caminho
            throw new \Exception($th->getMessage() . ' ' . $caminho);
        };
    }

    public function _nomeCamposDb()
    {
        return array(
            'id',
            'sinc_erp'
        );
    }
}