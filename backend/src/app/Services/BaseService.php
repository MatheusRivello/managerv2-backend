<?php

namespace App\Services;

use App\Models\Central\Empresa;
use App\Models\Tenant\Filial;
use App\Models\Tenant\Log;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * @property string $tabela
 */
class BaseService
{
    private $tenantId;

    protected function messages()
    {
        $messages = [
            'required' => 'O campo :attribute é obrigatorio.',
            'unique' => 'Já existe um registro com este dado (:attribute).',
            'min' => 'O campo :attribute deve conter no minimo :min caracteres.',
            'max' => 'O campo :attribute deve ter no maximo :max caracteres.',
            'boolean' => 'O campo :attribute deve ser um dos tipos: TRUE e FALSE ou 1 e 0.',
            'email' => 'O campo (:attribute) deve ser um endereço de email válido.',
            'string' => 'O campo (:attribute) deve ser uma string válida.',
            'integer' => 'O campo (:attribute) deve ser uma número válido.',
            'date' => 'O campo (:attribute) deve ser uma data válida.',
            'array' => 'O campo (:attribute) deve ser um array.',
            'numeric' => 'O campo (:attribute) deve ser um número.',
            'exists' => 'Existe um valor inválido informado no campo (:attribute), pois não se encontra em nossa base de dados.',
            'in' => 'O valor informado no campo (:attribute) é inválido, pois não se encontra dentro dos parametros [:values].',
            'after_or_equal' => ' Esta data (:attribute) precisa ser posterior a data inicial',
            'before_or_equal' => ' Esta data (:attribute) precisa ser anterior a data final',
            'size' => [
                'numeric' => 'O :attribute deve ser no máximo :size.',
                'file' => 'O arquivo :attribute deve ter no máximo :size kilobytes. (Info: 1 MB = 1024 KB)',
                'string' => 'O :attribute deve ter no máximo :size caracteres.',
                'array' => 'O array :attribute deve ter no máximo :size itens.',
            ],
            'uploaded' => 'Ocorreu uma falha no upload do campo :attribute.',
            'between' => [
                'numeric' => 'O campo :attribute deve ser entre :min e :max.',
                'file'    => 'O campo :attribute deve ser entre :min e :max kilobytes.',
                'string'  => 'O campo :attribute deve ser entre :min e :max caracteres.',
                'array'   => 'O campo :attribute deve ter entre :min e :max itens.',
            ],
            'image' => 'O campo :attribute deve ser uma imagem, extensões permitidas: jpg, jpeg, png, bmp, gif, svg ou webp.',
            'mimes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
            'upload.max' => 'O arquivo :attribute deve ter no máximo :size kilobytes. (Info: 1 MB = 1024 KB)',
            // personalizado
            "email.unique" => 'Já existe um cadastro com este email.',
            "email.email" => 'Email invalido.',
            "telefone.max" => 'Telefone invalido, máximo de caracteres: :max.',
            "usuario.min" => 'Usuário invalido, minimo de caracteres: :min.',
            "usuario.max" => 'Usuário invalido, máximo de caracteres: :max.',
            "usuario.unique" => 'Já existe um usuário com este login.',
            "confirmed" => "A confirmação da senha não corresponde.",
            "token.size" => 'O token deve possuir :size caracteres',
            "date_format" => 'o campo :attribute deve ser uma hora ou uma data válida'
        ];

        return $messages;
    }

    public function verificarErro($dados)
    {   
        if (count($dados) == 0) {
            $messageNotFound = [
                "message" => REGISTRO_NAO_ENCONTRADO
            ];
        }

        return (is_Object($dados) && !is_null($dados) || is_countable($dados) && count($dados) > 0) ? $dados : $messageNotFound;
    }

    public function usuarioLogado()
    {
        $user = Auth::guard()->user();
        
        if ($user) {
            return $user;
        }
        else {
            $user = new \stdClass();
            $user->fk_empresa = $this->tenantId;
            return $user;
        }
    }

    /**
     * Setta manualmente o id do tenant. Necessário quando o BaseService é
     * instanciado dentro de Jobs.
     */
    public function setManualTenantId($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    function array_random($array, $amount = 1)
    {
        $keys = array_rand($array, $amount);

        if ($amount == 1) {
            return $array[$keys];
        }

        $results = [];

        foreach ($keys as $key) {
            $results[] = $array[$key];
        }

        return $results;
    }

    public function infoTenant()
    {
        if (!isset($this->usuarioLogado()->fk_empresa)) {
            return "system";
        }

        $empresa = Empresa::find($this->usuarioLogado()->fk_empresa);

        if (isset($empresa)) {
            return $empresa;
        } else {
            throw new Exception(SEM_EMPRESA_RELACIONADA, 409);
        }
    }

    public function infoFilialTenant($id = null)
    {

        $filiais = collect(Filial::on($this->connection('tenat'))->get())->map(function ($tenant) {
            return $tenant;
        });

        return isset($id) ?  $filiais->firstWhere('id', $id) : $filiais->all();
    }

    public function connection($tipo = "service")
    {
        if (!isset($this->infoTenant()->id)) {
            return "system";
        }

        switch ($tipo) {
            case 'service':
                $dado = "empresa{$this->infoTenant()->id}.{$this->infoTenant()->bd_nome}";
                break;

            case 'database':
                $dado = $this->infoTenant()->bd_nome;
                break;

            default:
                $dado = "empresa{$this->infoTenant()->id}";
                break;
        }

        return $dado;
    }

    public function formatarHora($request)
    {
        $request = intval($request);

        if (
            $request < 10
        ) {
            $request = "0" . $request . ":00";
        } else if ($request > 9 && $request < 24) {
            $request = $request . ":00";
        } else {
            $request = "00:00";
        }

        return $request;
    }

    public function verificarCamposRequest($request, $ClasseRule, $id = null, $idBaseRegistro = null, $typeReturn = NULL)
    {
        $newRequest = (is_array($request) ? $request : $request->all());

        $validator = Validator::make(
            $newRequest,
            (new $ClasseRule)::rules($id, $this->connection()),
            $this->messages()
        );

        $errors = $validator->errors();

        $textoErrors = [];

        foreach ($errors->all() as $message) {
            $textoErrors[] = $message . ' ';
        }

        if ($validator->fails()) {
            switch ($typeReturn) {
                case "return":
                    return $textoErrors;
                    break;

                default:
                    throw new Exception(implode($textoErrors), 207);
                    break;
            }
        } else {
            return $newRequest;
        }
    }

    public function salvarArquivo($file, $tipo, $tenant = BD_CENTRAL, array $tamanhoImg = [1280, 720], $pathPacote = NULL, $nameFile = NULL)
    {
        $extensao = $file->getClientOriginalExtension();
        $extensao = empty($extensao) ? "jpg" : $extensao;
        $idEmpresa = $this->usuarioLogado()->fk_empresa;

        switch ($tipo) {
            case TIPO_IMAGEM:
                $prefixFile = "image_";
                $subPath = PATH_IMAGEM;
                $extensao = EXTENSAO_IMAGEM;
                break;

            case TIPO_DOCUMENTO:
                $prefixFile = "documento_";
                $subPath = PATH_DOCUMENTO;
                break;

            case TIPO_VIDEO:
                $prefixFile = "video_";
                $subPath = PATH_VIDEO;
                break;

            case OUTROS_TIPOS:
                $prefixFile = "outros_";
                $subPath = PATH_OUTROS;
                break;

            case TIPO_ZIP:
                $prefixFile = "{$idEmpresa}-";
                $subPath = PATH_ZIP;
                break;

            case TIPO_SERVICO_ZIP:
                $prefixFile = $nameFile;
                $subPath = $pathPacote;
                break;

            default:
                $prefixFile = "afv_";
                $subPath = PATH_OUTROS;
                break;
        }

        $path = $this->getPathFile($tenant, $idEmpresa) . $subPath;

        if (!isset($pathPacote)) {
            $filename = uniqid($prefixFile) . "." . $extensao;
        } else {
            $filename = $prefixFile . "." . $extensao;
            $path = $pathPacote;
        }

        if (explode("/", $file->getmimeType())[0] == "image") {
            $img = Image::make($file->getRealPath());
            $img->resize($tamanhoImg[0], $tamanhoImg[1], function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->stream(); // <-- Key point    

            Storage::disk(DRIVE_DEFAULT)->put($path . $filename, $img);
        } else {
            $file->storeAs($path, $filename, DRIVE_DEFAULT);
        }

        return $path . $filename;
    }

    private function getPathFile($tipo, $idTenant = null)
    {
        $tenant = isset($idTenant) ? "emp-" .  $idTenant : "outros";
        return  $tipo ? "central/" : "tenant/" . $tenant . "/";
    }

    public function deleteArquivo($path, $driveStorage = DRIVE_DEFAULT)
    {
        if (isset($path) && file_exists("storage/" . $path) && !Storage::disk($driveStorage)->delete($path)) {
            throw new Exception(DELETE_FILE_ERROR, 409);
        }
    }

    public function listDriversTenants($idEmpresa = null)
    {
        $conexoesTenants = collect(Config::get('database.connections'))->reject(function ($item, $key) {
            if (!str_contains($key, "empresa")) {
                return ($item);
            }
        });

        if (isset($idEmpresa) && !isset($conexoesTenants["empresa" . $idEmpresa])) {
            throw new Exception(REGISTRO_NAO_ENCONTRADO, 404);
        }

        return isset($idEmpresa) ? $conexoesTenants["empresa" . $idEmpresa] : $conexoesTenants->all();
    }

    public function verificaServicoLocal($dados)
    {
        $empresa = Empresa::where('id', $dados->fk_empresa)->first();

        if (!isset($empresa)) {
            return;
        }

        $retorno = [
            "id" => $empresa->id,
            "cliente" => $empresa->razao_social,
            "status" => FALSE,
            "descricao" => "Serviço fora do ar"
        ];

        if (!is_null($dados)) {
            $datetime1 = date_create(date("Y-m-d H:i:s"));
            $datetime2 = date_create($dados["dt_atualizado"]);

            $interval = date_diff($datetime1, $datetime2);

            $intervalo = "";

            $i = explode("|", $interval->format('%Y|%m|%d|%h|%i|%s'));

            //ano
            if (intval($i[0]) > 0) {
                $intervalo .= "$i[0] ano(s) ";
            }
            //mes
            if (intval($i[1]) > 0) {
                $intervalo .= "$i[1] mes(es) e ";
            }
            //dia
            if (intval($i[2]) > 0) {
                $intervalo .= "$i[2] dia(s) ";
            }

            //horas
            if (intval($i[3]) > 0) {
                $intervalo .= "$i[3]:";
            }
            //minuto
            if (intval($i[4]) > 8) {
                $intervalo .= "$i[4] ";
            }
            //hora
            if (intval($i[3]) > 0) {
                $intervalo .= "hora(s)";
            } else if (intval($i[3]) < 1 && intval($i[4]) > 8) {
                $intervalo .= "minuto(s)";
            }

            if ($intervalo == "") {
                $retorno = [
                    "id" => $empresa->id,
                    "cliente" => $empresa->razao_social,
                    "status" => TRUE,
                    "descricao" => "Serviço ativo"
                ];
            } else {
                $retorno = [
                    "id" => $empresa->id,
                    "cliente" => $empresa->razao_social,
                    "status" => FALSE,
                    "descricao" => $intervalo
                ];
            }
        }

        return $retorno;
    }

    public function verificaID($id)
    {
        if (!isset($id)) {
            throw new Exception(DADO_NAO_INFORMADO, 409);
        }
    }

    public function connectionTenant($idEmpresa, $tipo = null)
    {
        $empresa = isset($tipo) ? "." . Empresa::where('id', $idEmpresa)->select("bd_nome")->first()->bd_nome : null;
        return PREFIXO_TENANT . "{$idEmpresa}{$empresa}";
    }

    public function getIdNuvem2($tipo = 0, $nomeModel, $coluna, $idExterno, $verificacao = TRUE)
    {

        switch ($tipo) {
            case 0:
                $model = CAMINHO_CENTRAL . $nomeModel;
                break;
            case 1:
                $model = CAMINHO_TENANT . $nomeModel;
                break;
            default:
                $model = CAMINHO_CENTRAL . $nomeModel;
        }

        $query = (new $model)::select($coluna)->where($idExterno)->first();

        if ($verificacao == TRUE && isset($query)) {
            $resultado = $idExterno[$coluna];
        } else if ($verificacao == TRUE && !isset($query)) {
            $descricaoModel = str_replace("\/", "", $nomeModel);
            throw new Exception(REGISTRO_NAO_ENCONTRADO . ",Tabela:{$nomeModel}, Filtros utilizados: " . json_encode($idExterno), 404);
        } else if ($verificacao == FALSE && isset($query)) {
            $resultado = $query;
        } else {
            throw new Exception(ERRO_CONSULTA_DINAMICA, 500);
        }

        return  $resultado;
    }

    public function countChaveComposta($tipo = 0, $nomeModel, $where = [])
    {

        switch ($tipo) {
            case 0:
                $model = CAMINHO_CENTRAL . $nomeModel;
                break;
            case 1:
                $model = CAMINHO_TENANT . $nomeModel;
                break;
            default:
                $model = CAMINHO_CENTRAL . $nomeModel;
        }

        $query = (new $model)::where($where)->count();
        // dd($query);
        if ($query > 0) {
            throw new Exception(EXISTE_REGISTRO, 400);
        }

        return true;
    }

    /**Monta um vetor de filiais com id_retaguarda(chave), id(conteudo)
     * @param $tabela
     * @param string $chave (Nome do campo para ser a chave do vetor)
     * @param string $valor (Nome do campo para ser o parametro da chave)
     * @param bool $indexExtra (Caso seja necessário incluir mais um index depois da filial ex: vetor['filial']['grupo'])
     * @param null $where
     * @return array
     */
    public function getAllIds($tabela = NULL, $chave = "id_retaguarda", $valor = "id", $indexExtra = FALSE, $where = NULL)
    {
        $tabela = (!is_null($tabela)) ? $tabela : $this->tabela;
        $vetor = null;

        $select = ["id_filial", $chave, $valor];

        if ($indexExtra != FALSE && !is_null($indexExtra)) array_push($select, $indexExtra);

        $query = DB::connection($this->connectionTenant($this->usuarioLogado()->fk_empresa))->table($tabela)
            ->select($select);

        if (!is_null($where)) $query->where($where);

        $query = $query->get();

        if (!is_null($query)) {
            foreach ($query as $item) {
                if ($indexExtra == FALSE) {
                    $vetor[trim($item->id_filial)][trim($item->$chave)] = trim($item->$valor);
                } else {
                    $vetor[trim($item->id_filial)][trim($item->$indexExtra)][trim($item->$chave)] = trim($item->$valor); //atribuindo um index extra
                }
            }
        }

        return $vetor;
    }

    /**Monta um vetor com id(chave), id(conteudo)
     * @param $tabela
     * @param $idRetaguarda (TRUE=Atribui o indice com o id retaguarda, FALSE= Atribui o indice com id interno)
     * @return array
     */
    public function getAllId($tabela = NULL, $idRetaguarda = FALSE)
    {
        $tabela = (!is_null($tabela)) ? $tabela : $this->tabela;
        $vetor = null;

        $select = $idRetaguarda ?  ["id", "id_retaguarda"] : ["id"];

        $query = DB::connection($this->connectionTenant($this->usuarioLogado()->fk_empresa))->table($tabela)
            ->select($select)->get();

        if (!is_null($query)) {
            foreach ($query as $item) {
                $id = ($idRetaguarda) ? $item->id_retaguarda : $item->id;
                $vetor[trim($id)] = trim($item->id);
            }
        }

        return $vetor;
    }

    public function getAllIdsOFF($tabela)
    {
        $vetor = NULL;
        $select = [DB::raw("CONCAT(id_produto,unidade) AS id_unico"), "id"];

        $query = DB::connection($this->connectionTenant($this->usuarioLogado()->fk_empresa))->table($tabela)
            ->select($select)->get();

        if (!is_null($query)) {
            foreach ($query as $item) {
                $vetor[$item->id_unico] = $item->id; //atribuindo um index extra
            }
        }

        return $vetor;
    }

    private function _camposDaTabelaDb($dados, $nomeCamposDb)
    {
        if (is_array($nomeCamposDb) && is_array($dados)) {
            $newDados = $this->_deletaChaveDoArrayNaoExistente($nomeCamposDb, $dados);
        }

        return $newDados;
    }

    public function _validacao($dados, $nomeCamposDb)
    {
        $returnDados = $this->_camposDaTabelaDb($dados, $nomeCamposDb);

        if (is_array($returnDados)) {
            return [
                "status" => "sucesso",
                "dados" => $returnDados
            ];
        } else {
            return [
                "status" => "erro",
                "mensagem" => "Campos incorretos"
            ];
        }
    }

    /**Verifica se os parametros passado existe no array criado se não tiver dentro do array campos ele
     * exclue o parametro
     * @param $campos (Array de campos existentes)
     * @param $dados (Array dados recebidos)
     */
    private function _deletaChaveDoArrayNaoExistente($campos, &$dados)
    {
        foreach ($dados as $chave => $valor) {
            if (!in_array($chave, $campos)) {
                unset($dados[$chave]);
            }
        }

        return $dados;
    }

    /**Retorna as mensagens que foram recebidas no TRY CATCH
     * @param $data
     * @return array
     */
    public function _mensagemExcecao($data, $codigoHTTP)
    {
        return [
            "code" => $codigoHTTP,
            "Erro" => $data
        ];
    }

    public function getDadosOpenConexao($mac = NULL, $idEmpresa = NULL)
    {
        if (!is_null($mac)) {

            $retorno = Empresa::select(
                "empresa.id",
                "dispositivo.id_vendedor",
                DB::raw("dispositivo.id as id_dispositivo"),
                "empresa.bd_host",
                "empresa.bd_porta",
                "empresa.bd_usuario",
                "empresa.bd_senha",
                "empresa.bd_nome"
            )
                ->join("dispositivo", "empresa.id", "=", "dispositivo.fk_empresa")
                ->where("dispositivo.mac", $mac)
                ->first();
        } else if (!is_null($idEmpresa)) {
            $retorno = Empresa::select("id", "bd_host", "bd_porta", "bd_usuario", "bd_senha", "bd_nome")
                ->where("id", $idEmpresa)
                ->first();
        } else {
            $retorno = FALSE;
        }

        return $retorno;
    }

    public function write_file(string $path, string $data, string $mode = 'wb')
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

    public function criarPasta($caminho)
    {
        if (!is_null($caminho) && $caminho !== "") {
            if (!file_exists($caminho)) {
                @mkdir($caminho); // com o @ na frente ele não mostra o erro
                $this->write_file($caminho . DIRECTORY_SEPARATOR . 'index.html', '<h3>Acesso Negado</h3>');
            }
        }
    }
    public function _criarCaminhoRequisicaoZip($token, $ultimaSincronizacao, $idEmpresa, $metodo = NULL)
    {
        $caminho = "";

        $pastaUploadPadrao = "tenant" . DIRECTORY_SEPARATOR . "emp-" . $idEmpresa . DIRECTORY_SEPARATOR;
        $pastaUploadEmpresa = CAMINHO_PADRAO_STORAGE . $pastaUploadPadrao;

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
            if (file_exists($caminho)) $this->delete_files($caminho, $todosArquivosDentroDoCaminho);
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

    public function convertStringToDate($str, $formatInput = 'Y-m-d H:i:s')
    {
        return DateTime::createFromFormat($formatInput, $str);
    }

    public function formatarMoedaBr($valor)
    {
        return number_format($valor, 2, ',', '.');
    }

    public function formatarMoedaUs($valor)
    {
        return number_format($valor, 2, '.', '');
    }

    public function duasCasasDecimais($valor)
    {
        return number_format($valor, 2);
    }

    public function formatarRealParaMilhar($param)
    {
        return str_replace(array('.', ','), array('', '.'), $param);
    }

    function transacao($tipo)
    {
        switch ($tipo) {
            case "abrir":
                DB::beginTransaction();
                break;
            case "fechar":
                DB::commit();
                break;
            case "falha":
                DB::rollBack();
                break;
            default:
                DB::rollBack();
                break;
        }
    }

    public function getBrasilAPI($cep)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "http://brasilapi.com.br/api/cep/v2/$cep?=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Accept: */*"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function getViaCEP($cep)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "http://viacep.com.br/ws/$cep/json/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Accept: */*"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function _getEnderecoGeolocalizacao($latitude, $longitude)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . KEY_API_MAPS_GOOGLE . "&latlng={$latitude},{$longitude}";

        $localizacao = json_decode(Http::withOptions(['verify' => false])->get($url), true);

        return ($localizacao["status"] == "OK" && !is_null($localizacao["results"][0]["formatted_address"])) ? $localizacao["results"][0]["formatted_address"] : NULL;
    }

    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }

    /**
     * Salva dados na tabela de log.
     * @param $tipo
     * @param $tabela
     * @param null $conteudo
     * @param null $mensagem
     * @param null $id_empresa
     * @param int $id_cliente
     * Colunas no banco: id_cliente, filial_id, ip, dt_cadastro, tabela, mensagem, conteudo, tipo, tipo_acesso, id_empresa, mac
     * @param null $mac
     * @return mixed
     */
    function _salvar_log($tipo, $tabela, $conteudo = NULL, $mensagem = NULL, $id_empresa = NULL, $id_cliente = NULL, $mac = NULL)
    {
        $dados = [
            'tipo' => $tipo,
            'tabela' => $tabela,
            'conteudo' => $conteudo,
            'mensagem' => $mensagem,
            'id_empresa' => $id_empresa,
            'id_cliente' => $id_cliente,
            'mac' => $mac,
            'ip' => $this->getIp()
        ];

        $logTenant = new Log();
        $logTenant->tipo = $tipo;
        $logTenant->tabela = $tabela;
        $logTenant->conteudo = $conteudo;
        $logTenant->mensagem = $mensagem;
        $logTenant->id_empresa = $id_empresa;
        $logTenant->id_cliente = $id_cliente;
        $logTenant->mac = $mac;
        $logTenant->ip = $this->getIp();

        return $logTenant->save() ? $logTenant->id : NULL;
    }

    function slugify($string)
    {
        $string = preg_replace('/[\t\n]/', ' ', $string);
        $string = preg_replace('/\s{2,}/', ' ', $string);
        $list = array(
            'Š' => 'S',
            'š' => 's',
            'Đ' => 'Dj',
            'đ' => 'dj',
            'Ž' => 'Z',
            'ž' => 'z',
            'Č' => 'C',
            'č' => 'c',
            'Ć' => 'C',
            'ć' => 'c',
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Æ' => 'A',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ø' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ý' => 'Y',
            'Þ' => 'B',
            'ß' => 'Ss',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'æ' => 'a',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ð' => 'o',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ý' => 'y',
            'ý' => 'y',
            'þ' => 'b',
            'ÿ' => 'y',
            'Ŕ' => 'R',
            'ŕ' => 'r',
            '/' => '-',
            ' ' => '-',
            '.' => '-',
        );

        $string = strtr($string, $list);
        $string = preg_replace('/-{2,}/', '-', $string);
        $string = strtolower($string);

        return $string;
    }

    public function parseCamelCase($field)
    {
        if (str_contains($field, "_") && $field !== "APRESENTA_VENDA") {
            return str_replace("_", "", lcfirst(ucwords($field, "_")));
        }
        return $field;
    }

    function parseDate($date)
    {
        $formatedDate = [];
        for ($i = 0; $i < count($date); $i++) {
            $formatedDate[$i] = date('Y-m-d', strtotime($date[$i]));
        }
        return $formatedDate;
    }
}
