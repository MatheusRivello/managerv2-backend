<?php

namespace App\Services\Util;

use App\Mail\envioEmailPadrão;
use App\Models\Central\CabecalhoRequisicaoZip;
use App\Models\Central\CorpoRequisicaoZip;
use App\Services\BaseService;
use App\Services\fcm\FcmService;
use Exception;
use ZipArchive;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ErpService
{
    private $caminho = "";
    private $caminhoTemp = "";
    private $caminhoDefault = "";
    private $idEmpresa;
    private $conexaoBD;
    private $usuarioLogado;
    private $nomeCamposDb;
    private $tabela;

    public function __construct($usuarioLogado, $caminho, $caminhoTemp, $caminhoDefault, $conexaoBD, $nomeCamposDb, $tabela)
    {
        $this->usuarioLogado = isset($usuarioLogado) ? $usuarioLogado : NULL;
        $this->idEmpresa = $this->usuarioLogado->fk_empresa;
        $this->caminho = $caminho;
        $this->caminhoTemp = $caminhoTemp;
        $this->caminhoDefault = $caminhoDefault;
        $this->conexaoBD = $conexaoBD;
        $this->nomeCamposDb = $nomeCamposDb;
        $this->tabela = $tabela;
        $this->service = new BaseService();
    }

    public function arrayTabelasQuePodemSerLimpas()
    {
        return [
            "Vendedorcliente_service" => TABELA_VENDEDOR_CLIENTE,
            "Notafiscal_service" => TABELA_NOTA_FISCAL,
            "Produtodesctoqtd_service" => TABELA_PRODUTO_DESCTO_QTD,
            "Titulofinanceiro_service" => TABELA_TITULO_FINANCEIRO,
            "Formaprazopgto_service" => TABELA_FORMA_PRAZO_PGTO,
            "Meta_service" => TABELA_META,
            "Aviso_service" => TABELA_AVISO,
            "Vendedorprotabelapreco_service" => TABELA_VENDEDOR_PROTABELA_PRECO,
            "Vendedorproduto_service" => TABELA_VENDEDOR_PRODUTO,
            "Mixproduto_service" => TABELA_MIX_PRODUTO,
            "Produtotabelaitens_service" => TABELA_PROTABELA_ITENS,
            "Endereco_service" => TABELA_ENDERECO,
            "Contato_service" => TABELA_CONTATO,
            "Clientetabelagrupo_service" => TABELA_CLIENTE_TABELA_GRUPO,
            "Configuracaofilial_service" => TABELA_CONFIGURACAO_FILIAL,
            "Indicadormargem_service" => TABELA_INDICADOR_MARGEM,
            "Produtoipi_service" => TABELA_PRODUTO_IPI,
            "Produtost_service" => TABELA_PRODUTO_ST,
            "Clienteformapgto_service" => TABELA_CLIENTE_FORMA_PGTO,
            "Clienteprazopgto_service" => TABELA_CLIENTE_PRAZO_PGTO,
            "Produtoembalagem_service" => TABELA_PRODUTO_EMBALAGEM,
            "Vendaplano_service" => TABELA_VENDA_PLANO,
            "Vendaplanoproduto_service" => TABELA_VENDA_PLANO_PRODUTO,
            "Vendedorprazo_service" => TABELA_VENDEDOR_PRAZO,
            "Campanhaparticipante_service" => TABELA_CAMPANHA_PARTICIPANTE,
            "Campanhamodalidade_service" => TABELA_CAMPANHA_MODALIDADE,
            "Campanharequisito_service" => TABELA_CAMPANHA_REQUISITO,
            "Campanhabeneficio_service" => TABELA_CAMPANHA_BENEFICIO,
            "Campanha_service" => TABELA_CAMPANHA,
            "Clientetabelapreco_service" => TABELA_CLIENTE_TABELA_PRECO,
        ];
    }

    public function arrayTabelasQuePodemSerInativadas()
    {
        return [
            "Statuscliente_service" => TABELA_STATUS_CLIENTE,
            "Statusproduto_service" => TABELA_STATUS_PRODUTO,
            "Formapgto_service" => TABELA_FORMA_PAGAMENTO,
            "Prazopgto_service" => TABELA_PRAZO_PAGAMENTO,
            "Atividade_service" => TABELA_ATIVIDADE,
            "Tipopedido_service" => TABELA_TIPO_PEDIDO,
            "Motivo_service" => TABELA_MOTIVO,
        ];
    }

    public function criarPacoteSinc($service, $json)
    {
        if (!is_dir($this->caminho)) {
            mkdir($this->caminho, 0755, true);
        }

        $this->gravarArquivo($this->caminhoTemp, $json, "json", "json", "a+");

        $this->criarZip($this->caminhoTemp, "json", $this->caminho . DIRECTORY_SEPARATOR . $service . '_service');
    }

    public function descompactarPacoteSinc($service)
    {
        $service = $service . '_service';
        $this->descompactarZip($this->caminho, $this->caminhoTemp, $service);
    }

    public function montarEstruturaPasta($token, string $metodo = "roteiro")
    {
        return $this->_criarCaminhoRequisicaoZip($token, $this->retornaUltSinc(), $metodo);
    }

    public function _criarCaminhoRequisicaoZip($token, $ultimaSincronizacao, $metodo = NULL)
    {
        $caminho = "";

        $pastaUploadPadrao = "storage" . DIRECTORY_SEPARATOR . "Tenant";
        $pastaUploadEmpresa = $this->caminho;

        //Caso a solicitação desse método seja no roteiro ele entrará no if
        if (($metodo == "roteiro") && !is_null($ultimaSincronizacao) && $ultimaSincronizacao["status"] == "3") {
            //Deleta todas pastas/arquivos que ainda existem dentro da pasta zip da empresa informada
            $this->deletarCaminhoInteiro($pastaUploadEmpresa . "zip" . DIRECTORY_SEPARATOR);
        }

        //Verifica a existência das pastas da empresa e a pasta zip
        if (
            !file_exists($pastaUploadEmpresa) ||
            !file_exists($pastaUploadEmpresa . "zip")
        ) {
            $this->criarPasta($pastaUploadEmpresa); //Cria pasta da empresa
            $this->criarPasta($pastaUploadEmpresa . "zip"); //Cria pasta zip
        }

        //Verifica a existência da pasta token
        if (
            !file_exists($pastaUploadEmpresa . "zip" . DIRECTORY_SEPARATOR . $token)
        ) {
            $this->criarPasta($pastaUploadEmpresa . "zip" . DIRECTORY_SEPARATOR . $token); //Cria pasta da requisição
        }

        //se informar o metodo ele criará a pasta
        if (!is_null($metodo) && $metodo != "roteiro") {
            $this->criarPasta($pastaUploadEmpresa . "zip" . DIRECTORY_SEPARATOR . $token . DIRECTORY_SEPARATOR . $metodo);
            $caminho = $metodo;
        }

        //Monta uma string com o caminho onde será salvo o arquivo
        $caminho = $pastaUploadEmpresa . "zip" . DIRECTORY_SEPARATOR . $token . DIRECTORY_SEPARATOR . $caminho;
        return $caminho;
    }

    public function deletarCaminhoInteiro($caminho, $todosArquivosDentroDoCaminho = TRUE)
    {
        if (!is_null($caminho) && $caminho !== "") {
            if (file_exists($caminho))
                $this->delete_files($caminho, $todosArquivosDentroDoCaminho);
        }
    }

    private function delete_files($path, $del_dir = FALSE, $htdocs = FALSE, $_level = 0)
    {
        $path = rtrim($path, '/\\');

        if (!$current_dir = @opendir($path)) {
            return FALSE;
        }

        while (FALSE !== ($filename = @readdir($current_dir))) {
            if ($filename !== '.' && $filename !== '..') {
                $filepath = $path . DIRECTORY_SEPARATOR . $filename;

                if (is_dir($filepath) && $filename[0] !== '.' && !is_link($filepath)) {
                    $this->delete_files($filepath, $del_dir, $htdocs, $_level + 1);
                } elseif ($htdocs !== TRUE or !preg_match('/^(\.htaccess|index\.(html|htm|php)|web\.config)$/i', $filename)) {
                    @unlink($filepath);
                }
            }
        }

        closedir($current_dir);

        return ($del_dir === TRUE && $_level > 0) ? @rmdir($path) : TRUE;
    }

    private function criarZip($caminho, $fileName, $service, $extensao = '.zip')
    {
        $zip = new ZipArchive;
        $fileName = $service . $extensao; // nome do zip
        $zipPath = public_path($fileName); // path do zip

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            // arquivos que serao adicionados ao zip
            $files = (File::class)::files(public_path($caminho));

            foreach ($files as $key => $value) {
                // nome/diretorio do arquivo dentro do zip
                $relativeNameInZipFile = basename($value);

                // adicionar arquivo ao zip
                $zip->addFile($value, $relativeNameInZipFile);
            }

            // concluir a operacao
            $zip->close();
            unlink(public_path($caminho . "json.json"));
        }
    }

    private function descompactarZip($caminhoOrigem, $caminhoTemp, $service)
    {
        $extensao = '.zip';
        $zip = new ZipArchive;
        $fileName = $service . $extensao; // nome do zip
        $zipPath = public_path($caminhoOrigem . $fileName); // path do zip
        $tempPath = $caminhoTemp . $service;

        if ($zip->open($zipPath) === TRUE && $zip->extractTo(public_path($tempPath)) == TRUE) {
            $zip->close();
            unlink(public_path($caminhoOrigem . $fileName));
        }
    }

    private function gravarArquivo($caminho, $conteudo, $nomeArquivo, $extensao, $metodo = "wb")
    {
        if (!is_dir($caminho)) {
            mkdir($caminho, 0755, true);
        }

        $fp = @fopen($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $caminho . $nomeArquivo . "." . $extensao, $metodo);
        fwrite($fp, $conteudo);
        fclose($fp);
    }

    public function criarPasta($caminho)
    {
        if (!file_exists($caminho)) {
            mkdir($caminho);
            $this->write_file($caminho . DIRECTORY_SEPARATOR . 'index.html', '<h3>Acesso Negado</h3>');
        }
    }

    private function write_file(string $path, string $data, string $mode = 'wb')
    {
        if (!$fp = @fopen($path, $mode)) {
            return FALSE;
        }

        flock($fp, LOCK_EX);

        for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result) {
            if (($result = fwrite($fp, substr($data, $written))) === FALSE) {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return is_int($result);
    }

    private function retornaUltSinc($tipo = NULL)
    {
        $ultimaSincronizacao = CabecalhoRequisicaoZip::select(DB::raw("status, dt_inicio_envio_pacotes AS ultimoEnvio"))
            ->whereRaw("fk_empresa = {$this->idEmpresa} AND tipo_requisicao IN ('2','3')", "dt_inicio_envio_pacotes")
            ->orderBy("dt_inicio_envio_pacotes", "DESC")
            ->limit(1)
            ->first();

        return json_decode($ultimaSincronizacao, true);
    }

    private function retornaTokenUltSinc($data)
    {
        $tipo = isset($tipo) ? $tipo : "'2','3'";

        $ultimaSincronizacao = CabecalhoRequisicaoZip::select("token")
            ->whereRaw("fk_empresa = {$this->idEmpresa} AND dt_inicio_envio_pacotes > '{$data}' AND tipo_requisicao IN ('2','3')", "dt_inicio_envio_pacotes")
            ->orderBy("dt_inicio_envio_pacotes", "DESC")
            ->limit(1)
            ->first();

        return json_decode($ultimaSincronizacao, true);
    }

    public function getJson($service, $pacoteAtual = 1)
    {
        $service = $service . "_service_" . $pacoteAtual;

        $dadosDoArquivo = $this->extractZipFile($this->caminho, $service, "json", 2);
        return $dadosDoArquivo;
    }

    public function extractZipFile($origem = NULL, $nomeDoZip = NULL, $nomeArquivoDentroDoZip = NULL, $deletarPastaPrincipal = NULL, $extencao = ".json")
    {
        $zipFile = new ZipArchive;

        //Abre o arquivo zip
        $openFile = $zipFile->open($origem . $nomeDoZip . ".zip");

        //Se abriu o zip
        if ($openFile) {

            //Extrai o arquivo zip para o mesmo diretório
            $zipFile->extractTo($origem);
            $zipFile->close();

            //Verifica se o arquivo zip esta no diretório
            if (file_exists($origem . $nomeArquivoDentroDoZip . $extencao)) {

                //Retorna o conteudo que esta dentro do txt
                $content = file_get_contents($origem . $nomeArquivoDentroDoZip . $extencao);

                //Verifica se existe algo dentro do arquivo txt
                if (!is_null($content) && $content != "") {

                    $resposta = [
                        'status' => 'sucesso',
                        'data' => json_decode(str_replace(["\/", "//"], "/", trim($content)), true)
                    ];

                    $pasta = substr_replace($origem, '', -1);

                    //Se for == 1 ele apagará todos os dados dentro da pasta principal
                    if ($deletarPastaPrincipal == 1) {
                        $this->_deletarArquivo($origem . $nomeDoZip);
                        $this->_deletarArquivo($origem . $nomeArquivoDentroDoZip . $extencao);
                        $this->_deletarPasta($pasta);
                    } else if ($deletarPastaPrincipal == 2) { //Se for == 2 ele apagará somente o arquivo extraido
                        $this->_deletarArquivo($origem . $nomeArquivoDentroDoZip . $extencao);
                    }
                } else {
                    $resposta = [
                        'status' => 'erro',
                        'mensagem' => 'Nao foi encontrado nenhum dado no ' . $extencao
                    ];
                }
            } else {
                $resposta = [
                    'status' => 'erro',
                    'mensagem' => 'Nao encontrou o arquivo em ' . $extencao
                ];
            }
        } else {
            $resposta = [
                'status' => 'erro',
                'mensagem' => 'Extracao dos aquivos falhou'
            ];
        }

        return $resposta;
    }

    /**Deleta somente arquivos
     * @param $caminho (URL, Exemplo: 'caminho/local/arquivo')
     */
    public function _deletarArquivo($caminho)
    {
        if (!is_null($caminho) && $caminho !== "" && !is_array($caminho)) {
            if (file_exists($caminho))
                @unlink($caminho); //com o (@) ele não mostra erros
        }
    }

    /**Deleta somente a pasta
     * @param $caminho (URL, Exemplo: 'caminho/local/arquivo')
     */
    public function _deletarPasta($caminho)
    {
        if (!is_null($caminho) && $caminho !== "") {
            if (file_exists($caminho))
                @rmdir($caminho); //com o (@) ele não mostra erros
        }
    }

    public function atualizarByToken($dados, $token)
    {
        $cabecalhoReqZip = CabecalhoRequisicaoZip::where('token', $token)->first();

        foreach ($dados as $coluna => $valor) {
            $cabecalhoReqZip->{$coluna} = is_array($valor) ? implode(',', $valor) : $valor;
        }

        $cabecalhoReqZip->update();
    }

    public function dadosParaExtracao($token)
    {
        $dadosParaExtracao = CabecalhoRequisicaoZip::select(
            "cabecalho_requisicao_zip.token",
            "cabecalho_requisicao_zip.caminho",
            "corpo_requisicao_zip.pacote_total",
            "corpo_requisicao_zip.metodo",
            "corpo_requisicao_zip.acao",
        )
            ->join("corpo_requisicao_zip", "corpo_requisicao_zip.fk_token_cabecalho_requisicao", "=", "cabecalho_requisicao_zip.token")
            ->where('token', $token)
            ->orderBy("corpo_requisicao_zip.dt_inicio_envio_pacote", "ASC")
            ->get();


        return $dadosParaExtracao;
    }

    public function atualizaTabelaSincronismo($tabela, $dados, $id = FALSE, $id_retaguarda = FALSE, $whereRaw = FALSE)
    {
        if ($whereRaw == FALSE) {
            $where = ($id_retaguarda === FALSE) ? ['id' => $id] : ['id_retaguarda' => $id_retaguarda];
        } else {
            $where = $whereRaw;
        }

        DB::table($tabela)->where($where)->update($dados);
    }

    public function atualizaTabelaSincronismoCentral($tabela, $dados, $id = FALSE, $id_retaguarda = FALSE, $whereRaw = FALSE)
    {
        if ($whereRaw == FALSE) {
            $where = ($id_retaguarda === FALSE) ? ['id' => $id] : ['id_retaguarda' => $id_retaguarda];
        } else {
            $where = $whereRaw;
        }

        DB::connection("system")->table($tabela)->where($where)->update($dados);
    }

    public function _validarArray($array)
    {
        if (!is_null($array) && is_array($array)) {
            return TRUE;
        } else {
            return [
                'code' => 404,
                'status' => 'error',
                'mensagem' => 'Nenhum item encontrado no JSON ou e invalido',
                'data' => $array
            ];
        }
    }


    protected function _atualizaPacotePorPacote($dadosParaExtracao, $caminhoPrincipal, $token, $tipoDeRequisicao, $SERVICE_CONTROLLER = NULL)
    {
        $resposta = NULL;
        $caminhoPasta = NULL;
        $metodoEmExecucao = "";
        $tamanhoArrayDados = count($dadosParaExtracao);
        $arrayIdsMetodos = [];
        $service = new BaseService();
        try {

            //Percorre metodo a metodo para inserir no banco
            for ($a = 0; $a < $tamanhoArrayDados; $a++) {
                $item = $dadosParaExtracao[$a];
                $item["caminho"] = isset($item["caminho"]) && $item["caminho"] != "" ? $item->caminho : $this->caminhoDefault . "zip" . DIRECTORY_SEPARATOR . $token . DIRECTORY_SEPARATOR;
                $metodoQuebrado = explode("-", $item["metodo"]);

                $class = $metodoQuebrado[1]; //[0] = "Id do metodo", [1] = "Nome da classe"
                $pasta = $item["caminho"] . $class;
                $metodoEmExecucao .= $metodoQuebrado[0]; //[0] = "Id do metodo", [1] = "Nome da classe"

                array_push($arrayIdsMetodos, $metodoQuebrado[0]); //Atribui o id de cada metodo em um array para salvar no log

                //Enquanto tiver loop ele irá atribuir a (,) virgula no final
                if ($a < $tamanhoArrayDados - 1)
                    $metodoEmExecucao .= ",";

                //Where para atualizar os campos no corpo do cabeçalho
                $whereAtualiazarCorpo = ["fk_token_cabecalho_requisicao" => $token, "metodo" => $item["metodo"]];

                $this->atualizarByToken(
                    ["metodo_em_execucao" => $metodoEmExecucao],
                    $token
                );

                $this->atualizaTabelaSincronismoCentral(
                    "corpo_requisicao_zip",
                    ["dt_inicio_execucao_pacote" => date("Y-m-d H:i:s"), "status" => STATUS_PACOTE_EXECUTANDO],
                    FALSE,
                    FALSE,
                    $whereAtualiazarCorpo
                );

                if (is_dir($pasta)) {
                    $resposta = [];
                    //Faz pacote por pacote
                    for ($i = 1; $i <= intval($item["pacote_total"]); $i++) {

                        $nomeDoZip = $class . "_" . $i; // Exemplo: cliente_1, cliente_2...
                        $caminhoArquivoZip = $pasta . DIRECTORY_SEPARATOR . $nomeDoZip . ".zip";

                        if (file_exists($caminhoArquivoZip)) {
                            $dadosDoArquivo = $this->extractZipFile($pasta . DIRECTORY_SEPARATOR, $nomeDoZip, "json", 2);

                            if ($dadosDoArquivo["status"] == "sucesso") {
                                $arrayDados = $dadosDoArquivo["data"];

                                $validarArray = $this->_validarArray($arrayDados);

                                if ($validarArray) {
                                    try {
                                        if (is_null($arrayDados) || count($arrayDados) == 0) {
                                            $atualizarDados = ERRO_PACOTE_VAZIO;
                                        } else {

                                            //Chama a classe e manda o id da empresa para o construtor do mesmo
                                            $classLower = strtolower($class);

                                            //Se for o primeiro pacote de cada classe ele verifica se pode limpar a tabela
                                            if ($i == 1) {
                                                if ($item["acao"] > 0) {
                                                    $respostaPrepararDados = $this->_percorreArrayComTabelasParaPrepararDados($class, $item["acao"]);

                                                    if ($respostaPrepararDados != false && $respostaPrepararDados["status"] == "erro") {
                                                        $resposta[$class] = $respostaPrepararDados;
                                                    }
                                                }
                                            }

                                            //Variavel $SERVICE_CONTROLLER - Nada mais é do que o nome da classe filha
                                            $atualizarDados = $SERVICE_CONTROLLER::atualizarDados($arrayDados, NULL);

                                            //Informa a classe da empresa que deu erro 500
                                            if (isset($atualizarDados["code"]) && $atualizarDados["code"] == HTTP_INTERNAL_SERVER_ERROR) {
                                                $resposta["erroCritico"][$class]["erroPastaClasse"]["code"] = ("********** ERRO-" . HTTP_INTERNAL_SERVER_ERROR . " NA CLASSE({$classLower}) EMPRESA_{$this->idEmpresa} **********");
                                            }
                                        }

                                        $resposta[$class]["atualizarDados"][$class . "-pacote_" . $i] = $atualizarDados;
                                    } catch (Exception $ex) {
                                        $resposta["erroCritico"][$class]["erroExcecaoAtualizarDados"][$class . "-pacote_" . $i][] = $service->_mensagemExcecao($ex, HTTP_INTERNAL_SERVER_ERROR);
                                    }
                                } else {
                                    $resposta["erroCritico"][$class]["erroValidarArray"][$class . "-pacote_" . $i][] = $validarArray;
                                }
                            } else {
                                $resposta["erroCritico"][$class]["erroExtracao"][$class . "-pacote_" . $i][] = $dadosDoArquivo;
                            }
                        } else {
                            $resposta["erroCritico"][$class]["erroPastaArquivoZip"][$class . "-pacote_" . $i][] = ["erro" => "Arquivo nao encontrado", "caminho" => $caminhoArquivoZip];
                        }
                    }
                } else {
                    $resposta["erroCritico"][$class]["erroPastaClasse"][] = ["erro" => "Pasta da classe nao encontrada", "caminho" => $pasta];
                }

                $this->atualizaTabelaSincronismoCentral(
                    "corpo_requisicao_zip",
                    ["dt_fim_execucao_pacote" => date("Y-m-d H:i:s"), "status" => STATUS_PACOTE_FINALIZADO],
                    FALSE,
                    FALSE,
                    $whereAtualiazarCorpo
                );
            }

            //Salva log quando for online ou programada
            if ($tipoDeRequisicao > 1) {
                $tipoDeRequisicao = ($tipoDeRequisicao == 2) ? "online" : "programada";

                //Remove os ids repetidos dentro do array
                $arrayIdsMetodosUnique = array_unique($arrayIdsMetodos, SORT_REGULAR);

                $dados = [
                    "tipo_acesso" => 1,
                    "tipo" => 2,
                    "tabela" => "integracao",
                    "conteudo" => json_encode([$tipoDeRequisicao => $arrayIdsMetodosUnique]),
                    "mensagem" => "integracao_executada",
                    "fk_empresa" => $this->idEmpresa,
                    "fk_usuario" => $this->usuarioLogado->id,
                    "ip" => request()->ip()
                ];

                $this->insertLogCentral($dados);
            }

            //Fim execução banco

        } catch (Exception $ex) {

            $resposta["erroCritico"]["erroExcecaoPacotePorPacote"][] = $service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }

        return $resposta;
    }

    public function insertLogCentral($dados)
    {
        DB::connection("system")->table("log")->insert($dados);
    }

    /**Percorre um array para verificar se existe o nome da tabela para limpar ou inativar os dados
     * @param $tipo (1=Limpar os dados, 2=inativar os dados)
     * @return bool
     */
    public function _percorreArrayComTabelasParaPrepararDados($class, $tipo)
    {
        
        $retorno = FALSE;

        if ($tipo == 1) { //Remove todos os registros da tabela

            foreach ($this->arrayTabelasQuePodemSerLimpas() as $classService => $tabelaBd) {
                if ($classService === $class) {
                    $retorno = $this->_prepararTabelaParaReceberNovosDados($tabelaBd, 1);
                    break;
                }
            }
        } else if ($tipo == 2) { //Inativa todos os registros da tabela
            foreach ($this->arrayTabelasQuePodemSerInativadas() as $classService => $tabelaBd) {
                if ($classService === $class) {
                    $retorno = $this->_prepararTabelaParaReceberNovosDados($tabelaBd, 2);
                    break;
                }
            }
        }

        return $retorno;
    }

    /**
     * LIMPA OU ATUALIZA OS DADOS DA TABELA PARA INATIVOS NO BANCO DE DADOS, MAS ANTES ELE EFETUA UM BACKUP DO BANCO
     * @param $json (contendo os Ids das tabelas do banco) Ex: {1,4,12,23}
     * @param $tipo (1=Limpar tabela, 2=Atualizar todos os registros para 0 'INATIVO')
     * @return boolean
     */
    protected function _prepararTabelaParaReceberNovosDados($tabela, $tipo)
    {
        $bdEmpresa = (new BaseService())->connection('database');

        $retorno = FALSE;
        if ($tipo == 1) {
            $retorno = $this->_limparTabela($bdEmpresa, $tabela);
        } else if ($tipo == 2) {
            $retorno = $this->_inativarOsDados($bdEmpresa, $tabela);
        }

        return $retorno;
    }

    protected function _limparTabela($bdEmpresa, $tabela)
    {
        try {
            $function = "truncate";
            $whereFunction = 0;
            $t[] = $tabela;
            if ($tabela == "nota_fiscal")
                $t[] = "nota_fiscal_item";
            if ($tabela == "meta")
                $t[] = "meta_detalhe";

            if ($tabela == "endereco" || $tabela == "contato") {
                $function = "deleteJoinCliente";
            }

            $retorno = $this->limparTabelaNOVO($bdEmpresa, $t, $function, $whereFunction);
        } catch (Exception $e) {
            $retorno = [
                'code' => 404,
                'status' => 'erro',
                "mensagem" => $this->service->_mensagemExcecao($e->getMessage(), 404)
            ];
        }

        return $retorno;
    }

    protected function _inativarOsDados($bdEmpresa, $tabela)
    {
        try {
            $t[] = $tabela;
            $retorno = $this->inativarTodosOsDados($bdEmpresa, $t);
        } catch (Exception $e) {
            $retorno = [
                'code' => 404,
                'status' => 'erro',
                "mensagem" => $this->service->_mensagemExcecao($e->getMessage(), 404)
            ];
        }

        return $retorno;
    }

    public function verificaExistenciaDaTabela($nomeBanco, $tabelaDb)
    {
        $query = DB::connection($this->conexaoBD)
            ->table("information_schema.tables")
            ->select("table_name")
            ->where("table_schema", $nomeBanco)
            ->where("table_name", $tabelaDb)
            ->get();

        return (count($query) > 0) ? TRUE : FALSE;
    }

    /**LIMPA TABELA POR TABELA DO BANCO
     * @param $bd_empresa object (Dados do banco que será efetuado as ações)
     * @param $tabela array (Lista com os nomes das tabelas)
     * @param $function (delete ou truncate)
     * @return boolean
     */
    public function limparTabelaNOVO($bdEmpresa, $tabela, $function = "truncate", $whereFunction = 0)
    {
        $erro = NULL;
        $retorno = NULL;
        $status = NULL;
        DB::beginTransaction();
        foreach ($tabela as $tabelaDb) {
            $tabelaExiste = $this->verificaExistenciaDaTabela($bdEmpresa, $tabelaDb);

            if ($tabelaExiste && $function == "truncate") {
                DB::connection($this->conexaoBD)
                    ->statement('SET FOREIGN_KEY_CHECKS=0');

                $status = DB::connection($this->conexaoBD)
                    ->table($tabelaDb)->truncate();

                DB::connection($this->conexaoBD)
                    ->statement('SET FOREIGN_KEY_CHECKS=1');
            } else if ($tabelaExiste && $function == "delete") {
                DB::connection($this->conexaoBD)
                    ->statement('SET FOREIGN_KEY_CHECKS=0');

                $status = DB::connection($this->conexaoBD)
                    ->table($tabelaDb)->where($whereFunction)->delete();

                DB::connection($this->conexaoBD)
                    ->statement('SET FOREIGN_KEY_CHECKS=1');
            } else if ($tabelaExiste && $function == "deleteJoinCliente") {
                DB::connection($this->conexaoBD)
                    ->statement('SET FOREIGN_KEY_CHECKS=0');

                $status = DB::connection($this->conexaoBD)
                    ->delete("DELETE $tabelaDb FROM $tabelaDb INNER JOIN cliente ON (cliente.id = {$tabelaDb}.id_cliente ) WHERE cliente.id_retaguarda IS NOT NULL;");

                DB::connection($this->conexaoBD)
                    ->statement('SET FOREIGN_KEY_CHECKS=1');
            } else {
                $erro[] = "Método desconhecido ($function) ou a tabela ($tabelaDb) não existe.";
            }
        }

        !is_null($status) && $status == 0 && !is_countable($erro) ? $erro[] = "Tabela = " . $tabelaDb . ". Filtro(s) = " . json_encode($whereFunction) . ". Registro não pode ser excluído ou não encontrado." : "";

        if (!is_countable($erro)) {
            DB::commit();
            $retorno = [
                'code' => HTTP_ACCEPTED,
                'status' => 'sucesso',
            ];
        } else {
            DB::rollBack();
            $retorno = [
                'code' => HTTP_NOT_FOUND,
                'status' => 'erro',
                'mensagem' => 'Falha ao limpar tabelas',
                'error' => $erro
            ];
        }

        return $retorno;
    }

    /**INATIVA OS REGISTROS DA TABELA
     * @param $bd_empresa object (Dados do banco que será efetuado as ações)
     * @param $tabela array (Lista com os nomes das tabelas)
     * @param $coluna (informada ou status)
     * @return boolean
     */
    public function inativarTodosOsDados($bdEmpresa, $tabela, $coluna = null)
    {
        $erro = NULL;
        $retorno = NULL;
        $status = NULL;

        DB::beginTransaction();
        foreach ($tabela as $tabelaDb) {
            $tabelaExiste = $this->verificaExistenciaDaTabela($bdEmpresa, $tabelaDb);

            if ($tabelaExiste) {
                $status = DB::connection($this->conexaoBD)
                    ->table($tabelaDb)->update([isset($coluna) ? $coluna : "status" => 0]);
            } else {
                $erro[] = "Tabela ($tabelaDb) não existe.";
            }
        }

        !is_null($status) && $status == 0 && !is_countable($erro) ? $erro[] = "Tabela = " . $tabelaDb . " Registro não pode ser encontrado ou já estão inativos." : "";

        if (!is_countable($erro)) {
            DB::commit();
            $retorno = [
                'code' => HTTP_ACCEPTED,
                'status' => 'sucesso',
            ];
        } else {
            DB::rollBack();
            $retorno = [
                'code' => HTTP_NOT_FOUND,
                'status' => 'erro',
                'mensagem' => 'Falha ao inativar registros',
                'error' => $erro
            ];
        }

        return $retorno;
    }

    public function zip($metodo, $acaoTabela, $request, $SERVICE_CONTROLLER)
    {
        $erro = NULL;
        $idCorpo = 0;
        $resposta = NULL;
        $dadosCabecalho = "";
        $token = isset($request->token) ? $this->idEmpresa . "-" . $request->token : NULL;
        $nomePacote = $metodo;
        $metodo = $request->metodo . "-" . $metodo;
        $acao = (isset($acaoTabela)) ? $acaoTabela : 0; //LIMPAR OU ATUALIZAR DB
        $pacote_total = $request->pacote_total;
        $pacote_atual = $request->pacote_atual;
        $execute = $request->execute;
        $service = new BaseService();
        try {
            if (is_null($token) || $token == "") {
                $resposta["tokenInvalido"] = [
                    "mensagem" => "Favor informe um token valido!",
                    "tokenEnviado" => is_null($token) ? "Nao foi recebido nenhum token" : $token
                ];

                throw new Exception("Favor informe um token valido!", 404);
            } else {
                $dadosCabecalho = CabecalhoRequisicaoZip::select(
                    "token",
                    "tipo_requisicao",
                    "recebendo_zip",
                    DB::raw("DATE_FORMAT(dt_inicio_envio_pacotes,'%d-%m-%Y %H:%i') AS dt_inicio_envio_pacotes_br"),
                    DB::raw("dt_inicio_envio_pacotes AS dt_inicio_envio_pacote_us")
                )
                    ->where("token", $token)
                    ->first();

                $recebendoZip = $dadosCabecalho->recebendo_zip;

                if ($execute != "1") {
                    $recebendoZip .= "," . $request->metodo;
                }

                $this->atualizarByToken(["recebendo_zip" => $recebendoZip], $dadosCabecalho->token);
                //Atualiza a configuração para recebendo zip
                if (intval($dadosCabecalho->tipo_requisicao) == TIPO_REQ_ONLINE) {
                    //Where para atualizar os campos no corpo do cabeçalho
                    $whereAtualiazarCorpo = ["fk_empresa" => $this->idEmpresa];

                    $this->atualizaTabelaSincronismo(
                        "periodo_sincronizacao",
                        ["baixar_online" => 2, "token_online_processando" => "{$dadosCabecalho->token}"],
                        FALSE,
                        FALSE,
                        $whereAtualiazarCorpo
                    );
                }

                $cadastroCorpo = CorpoRequisicaoZip::select("fk_token_cabecalho_requisicao")
                    ->whereRaw("fk_token_cabecalho_requisicao = '{$token}' AND metodo = '{$metodo}'")
                    ->first();

                //Sempre que for enviado o primeiro pacote de cada método ele salvará as informações no corpo
                if (is_null($cadastroCorpo) && $pacote_atual == 1) {
                    $corpoRequisicao = new CorpoRequisicaoZip();
                    $corpoRequisicao->fk_token_cabecalho_requisicao = $token;
                    $corpoRequisicao->metodo = $metodo;
                    $corpoRequisicao->acao = $acao;
                    $corpoRequisicao->pacote_total = $pacote_total;
                    $corpoRequisicao->dt_inicio_envio_pacote = date("Y-m-d H:i:s");
                    $corpoRequisicao->status = STATUS_PACOTE_RECEBENDO;
                    $corpoRequisicao->save();
                }

                if ($cadastroCorpo && !is_null($cadastroCorpo)) {
                    //Armazena o arquivo Zip no diretório
                    $salvarArquivoZip = $this->_salvarArquivoZip($token, $pacote_atual, $nomePacote, $request);

                    // Verifica se foi armazenado o arquivo zip
                    if ($salvarArquivoZip["status"] == "sucesso") {
                        //Sempre que for enviado o ultimo pacote de cada método ele salvará
                        if ($pacote_atual == $pacote_total) {
                            //Where para atualizar os campos no corpo do cabeçalho
                            $whereAtualiazarCorpoReq = ["fk_token_cabecalho_requisicao" => $token, "metodo" => $metodo];
                            $this->atualizaTabelaSincronismoCentral(
                                "corpo_requisicao_zip",
                                ["dt_inicio_execucao_pacote" => date("Y-m-d H:i:s"), "status" => STATUS_PACOTE_RECEBIDOS],
                                FALSE,
                                FALSE,
                                $whereAtualiazarCorpoReq
                            );
                        }


                        //Armazena o log dos arquivos salvos
                        unset($salvarArquivoZip["status"], $salvarArquivoZip["caminho"]);
                        $resposta[$token]["pacoteSalvo"] = $salvarArquivoZip;

                        //Se execute for igual a 1, ele irá começar a inserir no banco todas as informações enviadas via ZIP
                        if ($execute == "1") {

                            //Envia notificação para o dispositivo travar o botão de sincronismo
                            if (intval($dadosCabecalho->tipo_requisicao) > TIPO_REQ_CRITICA) { //1=Critica, 2=Online, 3=Programada

                                //Atualiza a configuração para executando
                                if (intval($dadosCabecalho->tipo_requisicao) == TIPO_REQ_ONLINE) {
                                    $whereAtualiazarCorpoReq = ["fk_empresa" => $this->idEmpresa];
                                    $this->atualizaTabelaSincronismo(
                                        "periodo_sincronizacao",
                                        ["baixar_online" => STATUS_PERIODO_EXECUTANDO, "dt_execucao_online" => date("Y-m-d H:i:s")],
                                        FALSE,
                                        FALSE,
                                        $whereAtualiazarCorpoReq
                                    );
                                }

                                $this->_enviarNotificacao($this->idEmpresa, date("Y-m-d H:i:s"), NUVEM_BLOQUEADA);
                            }

                            $this->atualizarByToken(
                                [
                                    "dt_fim_envio_pacotes" => date("Y-m-d H:i:s"),
                                    "dt_inicio_execucao_pacotes" => date("Y-m-d H:i:s"),
                                    "status" => STATUS_PACOTE_RECEBIDOS
                                ],
                                $token
                            );


                            $dadosParaExtracao = $this->dadosParaExtracao($token);

                            if (!is_null($dadosParaExtracao)) {

                                //Faz a extração de arquivo por aquivo e executa no banco

                                $resposta[$token]["processoDeExecucao"] = $this->_atualizaPacotePorPacote(
                                    $dadosParaExtracao,
                                    $dadosParaExtracao[0]["caminho"],
                                    $token,
                                    $dadosCabecalho["tipo_requisicao"],
                                    $SERVICE_CONTROLLER
                                );

                                $this->atualizarByToken(
                                    [
                                        "dt_fim_execucao_pacotes" => date("Y-m-d H:i:s"),
                                        "status" => STATUS_FINALIZADO_EXECUCAO_PACOTE
                                    ],
                                    $token
                                );
                            } else {
                                $erro["erroDadosParaExtracao"] = "Nao foi encontrado o cabecalho com o token ({$token})";
                            }

                            //Dispara notificação para os dispositivos da empresa para liberar o botão de sincronismo
                            if (intval($dadosCabecalho["tipo_requisicao"]) > 1) { //1=Critica, 2=Online, 3=Programada

                                //Se o tipo de requisição for online ele atualiazará
                                if (intval($dadosCabecalho["tipo_requisicao"]) == 2) {

                                    //Desmarca todas as opções marcadas na sincronização Online após ter terminado o processo de sincronizmo
                                    $whereConfigEmpresa = ["fk_empresa" => $this->idEmpresa, "tipo" => INTEGRACAO_ONLINE];

                                    $this->atualizaTabelaSincronismoCentral(
                                        "configuracao_empresa",
                                        ["valor" => STATUS_CONF_EMPRESA_INATIVO],
                                        FALSE,
                                        FALSE,
                                        $whereConfigEmpresa
                                    );

                                    //Atualiza a configuração para não baixar novamente a mesma configuração

                                    $wherePeriodoSync = ["fk_empresa" => $this->idEmpresa];

                                    $this->atualizaTabelaSincronismoCentral(
                                        "periodo_sincronizacao",
                                        ["baixar_online" => STATUS_PERIODO_NAO_BAIXAR, "dt_execucao_online_fim" => date("Y-m-d H:i:s")],
                                        FALSE,
                                        FALSE,
                                        $wherePeriodoSync
                                    );
                                }

                                $retornaTokenUltSinc = $this->retornaTokenUltSinc($dadosCabecalho["dt_inicio_envio_pacote_us"]);

                                if (empty($retornaTokenUltSinc) || is_null($retornaTokenUltSinc)) {
                                    $resposta[$token]["notificacaoDispositivo"] = $this->_enviarNotificacao($this->idEmpresa, $dadosCabecalho["dt_inicio_envio_pacote_br"], NUVEM_LIBERADA);
                                } else {
                                    $tipoRequisicao = $dadosCabecalho["tipo_requisicao"] == 2 ? "Online" : "Programada";

                                    $resposta[$token]["notificacaoDispositivo"] = [
                                        "type" => "sucesso",
                                        "message" => "Notificação não enviada pois existe uma nova requisição ({$tipoRequisicao}) em aberto!"
                                    ];
                                }
                            }
                        }
                    } else {
                        $erro["erroSalvarArquivoZip"] = $salvarArquivoZip;
                    }
                }
            }
        } catch (Exception $ex) {
            $erro["erroExcecao"] = $service->_mensagemExcecao($ex->getMessage(), 400);
        }

        if (!is_null($erro))
            $resposta[$token]["erroInicial"] = $erro;

        //Enviar e-mail de erros criticos
        if (!is_null($erro) || isset($resposta[$token]["processoDeExecucao"]["erroCritico"])) {

            $this->atualizarByToken([
                "dt_fim_execucao_pacotes" => date("Y-m-d H:i:s"),
                "status" => STATUS_ERRO_CRITICO_PACOTE
            ], $token);

            $erroInicial = (!is_array($erro)) ? array() : $erro;
            $erroCritico = array();
            if (isset($resposta[$token]["processoDeExecucao"]["erroCritico"]) && is_array($resposta[$token]["processoDeExecucao"]["erroCritico"])) {
                $erroCritico = $resposta[$token]["processoDeExecucao"]["erroCritico"];
            }

            $erroEmail = json_encode(array_merge($erroInicial, $erroCritico));
            $resposta[$token]["notificacaoDispositivo"] = $this->_enviarNotificacao($this->idEmpresa, $dadosCabecalho["dt_inicio_envio_pacote_br"], 1);

            //Caso de algum erro crítico o sistema se encarregará de enviar email para os programadores
            Mail::to(EMAIL_ERROS_SINCRONISMO)->send(new envioEmailPadrão('Erro de Sincronismo', $service->infoTenant(), $erroEmail));
        }

        return response()->json($resposta, 200);
    }

    private function retornaTokenUltSincronismo()
    {
        $ultimaSincronizacao = CabecalhoRequisicaoZip::select("token")
            ->whereRaw(
                "fk_empresa = {$this->idEmpresa} AND tipo_requisicao IN ('2','3')",
                "dt_inicio_envio_pacotes"
            )
            ->orderBy("dt_inicio_envio_pacotes", "DESC")
            ->limit(1)
            ->first();

        return json_decode($ultimaSincronizacao, true);
    }

    protected function _enviarNotificacao($idEmpresa = NULL, $dataInicioSincronismo, $tipo)
    {
        $fcm = new FcmService;
        $dados = $fcm->notificationSincronismo($idEmpresa, $dataInicioSincronismo, $tipo);

        if (is_null($dados)) {
            $retorno = [
                "type" => "error",
                "message" => "Não há dispositivos para notificar!"
            ];
        } else {
            $fcm->send_notification($dados);
            $retorno = [
                "type" => "ok",
                "message" => "Notificações encaminhadas para ({$dados["QTD_DEVICES"]}) Dispositivos!"
            ];
        }

        return $retorno;
    }

    /**Recebe aquivo em zip cria as pastas e salva no diretório
     * @param $token
     * @param $pacoteAtual
     * @param $metodo
     * @return array
     */
    protected function _salvarArquivoZip($token, $pacoteAtual, $metodo, $request)
    {
        // Define o caminho a salvar o pacote
        $caminho = $this->montarEstruturaPasta($token, $metodo);

        // Define o valor default para a variável que contém o nome da imagem 
        $class = $metodo . "_" . $pacoteAtual; //Ex: produto_1, produto_2...
        $config['upload_path'] = $caminho;
        $config["file_name"] = $class;
        $config['allowed_types'] = '*';
        $config['overwrite'] = TRUE;
        $config['max_size'] = 20500; //20MB
        $config['encrypt_name'] = FALSE;

        if (!isset($request->arquivo) && $request->hasFile('zip') && $request->file('zip')->isValid()) {
            $resposta = [
                'status' => 'erro',
                'descricao' => "Houve um imprevisto ao salvar o arquivo na posicao:({$pacoteAtual})",
                'caminho' => $caminho . $class,
                'mensagem' => 'strip_tags($this->upload->display_errors())'
            ];
        } else {
            // Recupera a extensão do arquivo
            $extension = $request->arquivo->extension();

            $service = new BaseService();
            // Define finalmente o nome
            $upload = $service->salvarArquivo($request->arquivo, TIPO_SERVICO_ZIP, BD_TENANT, [NULL], $caminho, $class);
            // $upload = $request->arquivo->storeAs($caminho, $nameFile, DRIVE_DEFAULT);

            $resposta = [
                'status' => 'sucesso',
                'data' => $class,
                'caminho' => $upload
            ];
        }

        return $resposta;
    }

    private function getNameController($field)
    {
        // CAMINHO_DEFAULT_CONTROLLER_DELPHI . DIRECTORY_SEPARATOR . 
        return CAMINHO_DEFAULT_CONTROLLER_DELPHI . DIRECTORY_SEPARATOR . str_replace("Service", "Servico", str_replace("_", "", (ucwords($field, "_")))) . "Controller";

        return $field;
    }
}