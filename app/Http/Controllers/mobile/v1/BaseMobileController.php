<?php

namespace App\Http\Controllers\mobile\v1;

use App\Http\Controllers\Controller;
use App\Models\Central\CabecalhoRequisicaoZip;
use App\Models\Central\Dispositivo;
use App\Models\Central\PeriodoSincronizacao;
use App\Models\Tenant\Vendedor;
use App\Services\BaseService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Auth;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

abstract class BaseMobileController extends Controller
{
    protected $mac;
    protected $versaoApp;
    protected $bdEmpresa;
    protected $tipoRestricao;
    protected $objetoRestricao;
    protected $idVendedorPorSupervisor = NULL; //Ids dos vendedores que pertencem ao supervisor
    protected $restricaoParaConsultaNoBanco;
    protected $service;
    protected $classeFilha;

    public function __construct($classeFilha, $mac, $versaoApp)
    {
        Auth::setDefaultDriver('mobile');

        $this->service = new BaseService();
        ini_set('memory_limit', '1256M');
        $this->autenticacao_mobile($mac);

        $this->mac = MAC_AUTHENTICATION;
        $this->versaoApp = $versaoApp;
        $this->bdEmpresa = $this->_validarMac($this->mac);

        $this->objetoRestricao = PeriodoSincronizacao::select("restricao_vendedor_cliente", "restricao_supervisor_cliente")
            ->where("fk_empresa", $this->bdEmpresa->id)->first();

        $this->_verificaSeEVendedorOuSupervisor();

        $this->restricaoParaConsultaNoBanco = $this->_verificaARestricaoParaConsultaNoBanco();

        //Retorna todos os vendedores que pertence a este supervisor incluindo ele mesmo
        if ($this->tipoRestricao->cargo == "supervisor") { //Verifica se o acesso é por supervisor
            $vendedores = Vendedor::select("id")->where('supervisor', $this->bdEmpresa->id_vendedor)->orderBy('id', 'asc')->get();

            if (!is_null($vendedores)) {
                foreach ($vendedores as $item) {
                    $this->idVendedorPorSupervisor[] = $item->id;
                }
            }
        }

        $this->classFilha = $classeFilha;
        // $this->_statusNuvem($classeFilha);
    }

    public function _downloadZip($nomeZip, $dados)
    {
        // enable output of HTTP headers
        $options = new Archive();
        $options->setSendHttpHeaders(true);
        $options->setEnableZip64(true);

        // create a new zipstream object
        $zip = new ZipStream("$nomeZip.zip", $options);

        foreach ($dados as $nomeJSON => $valorJSON) {
            $fp = tmpfile();
            fwrite($fp, $valorJSON);
            rewind($fp);
            $zip->addFileFromStream("$nomeJSON", $fp);
            fclose($fp);
        }

        $zip->finish();
    }

    // /**Retorna os dados relacionados ao vendedor ou supervisor*/
    protected function _retornoDadosPorVendedorOuSupervisor($tabela, $whereExtra = NULL)
    {
        //Caso for restrito para vendedor_cliente
        if ($this->restricaoParaConsultaNoBanco == "somenteDadosDoVendedor") {
            $data = $this->restricaoVendedorSupervisor($tabela, $this->bdEmpresa->id_vendedor, NULL, $whereExtra);
        } else if ($this->restricaoParaConsultaNoBanco == "dadosDeTodosOsVendedoresPorSupervisor") { //Caso for restrito por vendedores do supervisor
            $data = $this->restricaoVendedorSupervisor($tabela, $this->idVendedorPorSupervisor, NULL, $whereExtra);
        } else { //Caso não haja nenhuma restrição
            $data = $this->restricaoVendedorSupervisor($tabela, NULL, NULL, $whereExtra);
        }

        return $data;
    }

    public function restricaoVendedorSupervisor($tabela, $idVendedor = NULL, $select = NULL, $whereExtra = NULL)
    {
        $chaveCliente = ($tabela == "cliente") ? "id" : "id_cliente";

        if ($select == NULL) {
            $select = "{$tabela}.*";
        }

        $query = DB::connection($this->_getConnection())->table($tabela)
            ->select($select);

        if (!is_null($whereExtra)) $query->whereRaw($whereExtra);

        if (!is_null($idVendedor)) {
            $query->distinct(TRUE);
            $query->whereIn("vendedor_cliente.id_vendedor", is_array($idVendedor) ? $idVendedor : [$idVendedor]);
            $query->join("vendedor_cliente", "{$tabela}.{$chaveCliente}", "=", "vendedor_cliente.id_cliente");
        }

        return $query->get();
    }

    protected function _getAllPorTabela($tabela = NULL, $select = "*", $where = NULL)
    {
        $query = DB::connection($this->_getConnection())
            ->table($tabela)
            ->select(isset($select) ? $select : "*");

        if (!is_null($where)) $query->whereRaw($where);

        return count($query->get()) > 0 ? $query->get() : NULL;
    }

    public function getConfigComValorEsperado($descricao, $valorEsperado)
    {
        $query = DB::connection($this->_getConnection())->table(TABELA_CONFIGURACAO_FILIAL)
            ->select("*")
            ->where(["descricao" => $descricao, "valor" => $valorEsperado])->limit(1)->first();

        return isset($query->valor) ? $query->valor : NULL;
    }

    public function verificaExistenciaDaTabela($nomeBanco, $tabelaDb)
    {
        $query = DB::connection($this->_getConnection())->table("information_schema.tables")
            ->select("table_name")
            ->where(["table_schema" => $nomeBanco, "table_name" => $tabelaDb])->count();

        return ($query > 0) ? TRUE : FALSE;
    }

    protected function _verificarData($data)
    {
        return (!is_null($data)) ? json_encode($data) : NULL;
    }

    /**
     * @param $usuario
     * @param $senha
     * @return bool
     */
    public function autenticacao_mobile($mac)
    {
        if (strlen($mac) < 12 || strlen($mac) > 12) return FALSE;

        // [0]["fk_empresa"]
        $_SERVER['PHP_AUTH_USER'] = Dispositivo::select("fk_empresa")->where("mac", $mac)->first();

        if (!is_null($_SERVER['PHP_AUTH_USER'])) {
            define('MAC_AUTHENTICATION', $mac);
            return TRUE;
        } else {
            define('MAC_AUTHENTICATION', null);
        }
        return FALSE;
    }

    protected function _validarMac($mac)
    {
        if (strlen($mac) == 12) {
            $bdEmpresa = $this->service->getDadosOpenConexao($mac, NULL);

            if ($bdEmpresa == FALSE) {
                throw new Exception(MAC_INVALIDO, HTTP_NOT_ACCEPTABLE);
            } else if (is_null($bdEmpresa)) {
                throw new Exception(NAO_HA_DADOS_DO_BANCO_COM_MAC, HTTP_INTERNAL_SERVER_ERROR);
            }

            return $bdEmpresa;
        } else {
            $mensagem = json_encode([
                'code' => HTTP_NOT_FOUND,
                'status' => 'erro',
                'mensagem' => MAC_INVALIDO
            ]);

            throw new Exception($mensagem, HTTP_NOT_ACCEPTABLE);
        }
    }

    protected function _verificaSeEVendedorOuSupervisor()
    {
        $this->tipoRestricao = Vendedor::select(DB::raw("
        (CASE tipo
         WHEN '0' THEN 'vendedor'
         WHEN '1' THEN 'supervisor'
         WHEN '2' THEN 'gerente' 
         END) AS cargo"))
            ->where("id", $this->bdEmpresa->id_vendedor)->first();
    }

    protected function _verificaARestricaoParaConsultaNoBanco()
    {
        //Caso for restrito para vendedor_cliente
        if (
            ($this->objetoRestricao->restricao_vendedor_cliente == 1 && $this->tipoRestricao->cargo == "vendedor") || //Restrição por vendedor
            ($this->objetoRestricao->restricao_supervisor_cliente == 0 && $this->tipoRestricao->cargo == "supervisor")  //Restrição por supervisor inativado
        ) {
            $data = "somenteDadosDoVendedor";
        } else if ( //Caso for restrito por vendedores do supervisor
            $this->tipoRestricao->cargo == "supervisor" && //Acesso por supervisor
            $this->objetoRestricao->restricao_supervisor_cliente >= 2 //Restrição por supervisor
        ) {
            $data = "dadosDeTodosOsVendedoresPorSupervisor";
        } else { //Caso não haja nenhuma restrição
            $data = "todosOsDados";
        }

        return $data;
    }

    /**Verifica se a nuvem esta disponivel, senão estiver ele retorna com código de bloqueio*/
    protected function _statusNuvem($classeFilha)
    {
        $cabecalhoRequisicaoZip = CabecalhoRequisicaoZip::select("status")
            ->where([["fk_empresa", $this->bdEmpresa->id], ["tipo_requisicao", ">", "1"]])
            ->orderBy("dt_inicio_envio_pacotes", "DESC")->first();

        // 3 = Esta liberado para baixar os dados para o dispositivo
        if (is_null($cabecalhoRequisicaoZip->status) || $cabecalhoRequisicaoZip->status == 1 || $cabecalhoRequisicaoZip->status == 3) {
            $statusSincronismo = true;
        } else {
            $statusSincronismo = false;
        }

        if (
            !$statusSincronismo && ($classeFilha != "Pedido" || $classeFilha != "Rastro" ||
                $classeFilha != "Dispositivo" || $classeFilha != "Logdispositivo")
        ) {
            // throw new Exception(SINCRONIZANDO_NUVEM, HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function _getConnection()
    {
        $idEmpresa = auth('mobile')->user()->empresa->id;

        return PREFIXO_TENANT . "{$idEmpresa}";
    }

    protected function usuarioLogado()
    {
        return Auth::guard('mobile')->user();
    }

    protected function vendedoresPorSupervisorOuGerente($tipo = "supervisor")
    {
        switch ($tipo) {
            case "gerente":
                $coluna = "gerente";
                break;

            case "vendedor":
                $coluna = "id";
                break;

            default:
                $coluna = "supervisor";
                break;
        }

        $data = Vendedor::where($coluna, $this->usuarioLogado()->id_vendedor)->pluck("id");

        return $data;
    }

    protected function retorna_msg_erro($tipo, $mac = "", $json = "", $msg_extra = "", $id_empresa = "")
    {
        $resposta = NULL;
        switch ($tipo) {
            case ERRO_MAC_INVALIDO: //MAC INVÁLIDO
                $resposta = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'erro',
                    'mensagem' => 'Mac (' . $mac . ') inválido. ' . $msg_extra
                ];
                break;
            case ERRO_MAC_SEM_REGISTRO: // NÃO FOI ENCONTRADO NENHUM DADO COM O MAC
                $resposta = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'falha',
                    'mensagem' => 'Não há dados de nenhuma empresa com o MAC (' . $mac . '). ' . $msg_extra,
                ];
                break;
            case ERRO_JSON_INVALIDO: // JSON INVÁLIDO
                $resposta = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                    'mensagem' => 'Nenhum item encontrado no JSON ou é inválido. ' . $msg_extra,
                    'json' => $json
                ];
                break;
            case ERRO_VALIDACAO:
                $resposta = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                    'mensagem' => 'Não passou na validação ' . $msg_extra,
                    'error' => $json
                ];
                break;
            case ERRO_EMPRESA_SEM_DADOS: //NÃO ENCONTROU NADA COM A EMPRESA INFORMADA
                $resposta = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                    'mensagem' => 'Não há registros da emrpesa (' . $id_empresa . '). ' . $msg_extra,
                ];
                break;
            default:
                $resposta = FALSE;
        }

        return $resposta;
    }

    /**
     * @param $dataEmtrada (informe uma data no formato=Y-m-d)
     * @param $dataSaida (informe somente o formato para retorno)
     * @param $qtd (quantidade de dias,meses ou anos)
     * @param $operacao (+ ou -)
     * @param $tipo (months,days,years)
     * @return string
     */
    public function retrocederOuAvancarData($dataEntrada, $dataSaida = "d/m/Y", $qtd = "1", $operacao = "+", $tipo = "months")
    {
        $data = new DateTime($dataEntrada);
        $data->modify("{$operacao}{$qtd} {$tipo}");
        return $data->format($dataSaida);
    }
}
