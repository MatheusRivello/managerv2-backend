<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Tenant\Produto;
use App\Models\Tenant\ProdutoImagem;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image; // use Intervention
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Http\UploadedFile;

/**
 * @property ImagemServicoController $filha
 * @property string $PASTA_UPLOAD_EMPRESA
 * @property string $URL_ARQUIVO_ONLINE
 */
class ImagemServicoController extends BaseServicoController
{
    public function __construct(BaseService $service)
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PRODUTO_IMAGEM;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Imagem' . CLASS_SERVICE;
        $this->entity = ImagemServicoController::class;
        $this->firstOrNew = ["id"];
        $this->acaoTabela = 0;
        $this->idEmpresa = $service->usuarioLogado()->fk_empresa;
        $this->PASTA_UPLOAD_EMPRESA = $this->caminhoDefault;
        $this->URL_ARQUIVO_ONLINE = "emp-" . $this->idEmpresa . DIRECTORY_SEPARATOR;
    }

    /**
     * Recebe uma imagem e faz o tratamento
     * arquivo = arquivo/file. (obrigatorio)
     * tipo = produto, fabricante/marca, logo obrigatorio)
     * id = id do produto, ou fabricante
     * sequencia = sequencia da foto (opcional)
     * foto principal  = true ou false padrao false (opcional)
     */
    public function upload(Request $request)
    {
        $this->_verifica_pasta_empresa();
        $id_produto_interno = $this->_retorna_id_produto_interno($request->id_retaguarda , $request->id_filial);

        if (isset($id_produto_interno['status']) && $id_produto_interno['status'] = 'error') {
            return response()->json($id_produto_interno, $id_produto_interno['code']);
        }

        $sequencia = is_null($request->sequencia) ? 0 : $request->sequencia;
        $id_produto_md5 = md5($id_produto_interno);
        $nomeArquivo = md5($request->id_filial . $id_produto_interno . $request->sequencia);

        $pasta_produto = $this->URL_ARQUIVO_ONLINE . "produtos" . DIRECTORY_SEPARATOR . $id_produto_md5;

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

        return response()->json($response, HTTP_ACCEPTED);
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

    /**
     * Verifica se a pasta existe, caso não existir a mesma será criada.
     * @param $id_produto
     */
    private function _verifica_pasta_produto($id_produto)
    {
        $caminho = $this->PASTA_UPLOAD_EMPRESA . DIRECTORY_SEPARATOR . "produtos" . DIRECTORY_SEPARATOR . $id_produto;
        if (!file_exists($caminho)) {
            $this->_criar_pasta($caminho);
        }
    }

    /**Retorna o Id interno do prodtudo buscando pelo id_retaguarda e filial
     * @param $id_produto_retaguarda
     * @param  $id_filial
     * @return integer
     */
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

    /**
     * Atualiza O Sinc_erp atraves do id da nuvem
     */
    public function atualizaRastros(Request $request)
    {
        $log = [];
        $resposta = NULL;
        $resposta = $this->service->_validarArray($request->json);
        try {
            if ($resposta) {

                foreach ($request->json as $chave => $valor) {

                    $id_interno = $chave; // ID da nuvem
                    $valor_sinc_erp = $valor; // Valor do Sinc_erp deverá vir sempre (0) do sistema local

                    $validacao = $this->validaID($id_interno);

                    if (!$validacao) {
                        $log[$id_interno] = "INVALIDO";
                    } else {
                        $arrayDados = [
                            "id" => $id_interno,
                            "sinc_erp" => $valor_sinc_erp
                        ];

                        $resultado = self::atualizarInserir($arrayDados);

                        $log[$id_interno] = $resultado ? 'OK' : 'FALHA'; // Infoma se ouve Sucesso ou Erro
                    }
                }

                $resposta = (count($log) > 0) ? [
                    'code' => HTTP_ACCEPTED,
                    'status' => 'successo',
                    'mensagem' => $log
                ] : [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                ];
            }

            return response()->json($resposta, $resposta["code"]);
        } catch (Exception $ex) {
            return  $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function _nomeCamposDb()
    {
        return array(
            'id',
            'sinc_erp'
        );
    }

    // Verifisca se a pasta existe, caso não existir a mesma será criada.
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

    public function atualizarInserir($dados)
    {
        return parent::store($dados, Rastro::class, ["id"], self::_nomeCamposDb());
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
}
