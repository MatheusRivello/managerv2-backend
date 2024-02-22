<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Jobs\EnviaClienteErpJob;
use App\Models\Tenant\Cliente;
use App\Models\Tenant\ClienteReferencia;
use App\Models\Tenant\Contato;
use App\Models\Tenant\Endereco;
use App\Models\Tenant\Referencia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @property Cliente $model
 */
class ClienteMobileController extends BaseMobileController
{
    protected $className;
    protected $tenant;

    public function __construct(Request $request)
    {
        $this->className = "Cliente";
        $this->model = Cliente::class;
        $this->tenant = $this->usuarioLogado()->fk_empresa;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    public function clientejson(Request $request)
    {
        try {
            $array = json_decode($request->json, true);

            $ven_cod = $request->id_vendedor;

            $id_interno = 0;
            $resultado = FALSE;
            $erros = [];
            $retorno = NULL;
            $qtd_cliente_inserido = 0;

            if ($ven_cod === NULL || $ven_cod === "") {
                $resposta = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                    'mensagem' => "Informe o código do vendedor"
                ];

                goto mensagem;
            }

            //VERIFICA SE O JSON É INVÁLIDO
            if (is_null($array)) {
                $this->retorna_msg_erro(ERRO_JSON_INVALIDO, "", $array, "json");
                goto mensagem;
            }

            //LE TODOS OS DADOS DE CADA ARRAY DENTRO DO JSON
            foreach ($array as $item1) {
                $item1["cliente"]["ven_cod"] = $ven_cod;

                $validacao_cliente = $this->service->verificarCamposRequest($item1["cliente"], RULE_CLIENTE_SERVICO, null, null, 'return');

                if (isset($validacao_cliente["id_mobile"])) {
                    $this->service->transacao("abrir");
                    $item1["cliente"]["sinc_erp"] = 1;
                    $item1["cliente"]["razao_social"] = substr($item1["cliente"]["razao_social"], 0, 59);
                    $item1["cliente"]["nome_fantasia"] = substr($item1["cliente"]["nome_fantasia"], 0, 59);
                    $item1["cliente"]["tipo_contribuinte"] = strlen($item1["cliente"]["cnpj"]) > 11 ?: TIPO_CONTRIBUINTE_FISICA;

                    $id_interno = $this->getIdInterno($item1);

                    if (is_null($id_interno)) {
                        $id_interno = $this->inserirCliente($item1["cliente"]); // INSERSÃO DO CLIENTE

                        if (($id_interno <= 0) || ($id_interno === NULL)) {
                            $erros[] = ['erro' => 'falha ao inserir CLIENTE', 'recebido' => $item1["cliente"]];
                        } else {

                            //INSERSÃO CONTATO
                            foreach ($item1["contato"] as $contato) {
                                $validacao_contato = $this->service->verificarCamposRequest($contato, RULE_CONTATO_SERVICO, null, null, 'return');

                                if (isset($validacao_contato["id_mobile"])) {

                                    $id_contato_mobile = $contato["id_mobile"];
                                    unset($contato["id_mobile"]);
                                    $contato["id_cliente"] = $id_interno;
                                    $contato["sinc_erp"] = 1;
                                    $inserido_contato = $this->inserirContato($contato);
                                    $contato["id_mobile"] = $id_contato_mobile;

                                    if (!$inserido_contato) {
                                        // log_message("error", "Não foi possível cadastrar o contato do cliente CNPJ:{$item1["cliente"]["cnpj"]}");
                                        // log_message("error", print_r($item1, true));
                                        $erros[] = ['erro' => 'falha ao inserir Contato', 'recebido' => $contato];
                                    }
                                } else {
                                    $erros["validacao_erros"]["cliente-".$validacao_cliente["id_mobile"]]["contato"] = $validacao_contato;
                                }
                            }

                            //INSERSÃO ENDEREÇO
                            foreach ($item1["endereco"] as $endereco) {
                                $validacao_endereco = $this->service->verificarCamposRequest($endereco, RULE_ENDERECO_SERVICO, null, null, 'return');

                                if (isset($validacao_endereco["id_mobile"])) {
                                    $id_endereco_mobile = $endereco["id_mobile"];
                                    unset($endereco["id_mobile"]);
                                    $endereco["id_retaguarda"] = $id_interno . "." . $endereco["tit_cod"];
                                    $endereco["id_cliente"] = $id_interno;
                                    $endereco["sinc_erp"] = 1;
                                    $endereco["complemento"] = substr($endereco["complemento"], 0, 19); // só pegara 20 caracteres
                                    $endereco["logradouro"] = substr($endereco["logradouro"], 0, 39); // só pegara 40 caracteres
                                    $endereco["bairro"] = substr($endereco["bairro"], 0, 19); // só pegara 20 caracteres
                                    $endereco["numero"] = substr($endereco["numero"], 0, 9); // só pegara 10 caracteres

                                    $inserido_endereco = $this->inserirEndereco($endereco);

                                    if (!$inserido_endereco) {
                                        $endereco["id_mobile"] = $id_endereco_mobile;

                                        // log_message("error", "Não foi possível cadastrar o endereço do cliente CNPJ:{$item1["cliente"]["cnpj"]}");
                                        // log_message("error", print_r($item1, true));
                                        $erros[] = ['erro' => 'falha ao inserir Endereço', 'recebido' => $endereco];
                                    }
                                } else {
                                    $erros["validacao_erros"]["cliente-".$validacao_cliente["id_mobile"]]["endereco"] = $validacao_endereco;
                                }
                            }

                            //INSERSÃO REFERÊNCIA
                            foreach ($item1["referencia"] as $referencia) {
                                $validacao_referencia = $this->service->verificarCamposRequest($referencia, RULE_REFERENCIA_SERVICO, null, null, 'return');
                                if (isset($validacao_referencia["id_mobile"])) {

                                    unset($referencia["id_mobile"]);
                                    unset($referencia["id_cliente"]);

                                    $inserido_referencia = $this->inserirReferencia($referencia);

                                    if (!$inserido_referencia) {
                                        $erros[] = ['erro' => 'falha ao inserir Referencia', 'recebido' => $referencia];
                                    } else {
                                        $inserido_cliente_referencia = $this->inserirClienteReferencia(["id_cliente" => $id_interno, "id_referencia" => $inserido_referencia]);

                                        if (!$inserido_cliente_referencia) {
                                            // log_message("error", "Não foi possível cadastrar a referencia do cliente CNPJ:{$item1["cliente"]["cnpj"]}");
                                            // log_message("error", print_r($item1, true));
                                            $erros[] = ['erro' => 'falha ao inserir relacionamento cliente com refêrencia', 'recebido' => $referencia];
                                        }
                                    }
                                } else {
                                    $erros["validacao_erros"]["cliente-".$validacao_cliente["id_mobile"]]["referencia"] = $validacao_referencia;
                                }
                            }

                            //INSERÇÃO ANEXOS
                            // $this->salvarAnexos($id_interno, $item1["cliente"]["cnpj"]);
                        }
                    } else { //Já existe o cliente cadastradao
                        $qtd_cliente_inserido++;
                    }


                    if (count($erros) > 0) {
                        $erros[]["nao_inserido"] = ['erro' => 'falha ao inserir todos os dados do cliente (' . $item1["cliente"]["id_mobile"] . ")", 'recebido' => $item1];
                    } else {
                        $resultado = $this->service->transacao("fechar");
                        $idNuvem = isset($id_interno->id) ? $id_interno->id : $id_interno;
                        $retorno[] = [
                            "id_mobile" => $item1["cliente"]["id_mobile"],
                            "id_nuvem" => $idNuvem,
                        ];

                        $qtd_cliente_inserido++; // conta quantos clientes foram cadastrados

                        // cliente despachado para fila
                        EnviaClienteErpJob::dispatch($this->tenant, $idNuvem)->onQueue("cliente");
                    }
                } else {
                    $erros[] = $validacao_cliente;
                }
            }

            if ((!isset($retorno) || count($erros) > 0) && ($qtd_cliente_inserido > 0)) {
                $resposta = [
                    'code' => HTTP_NOT_ACCEPTABLE,
                    'status' => 'sucesso_parcial',
                    'mensagem' => 'Alguns dados não foram inseridos',
                    'data' => $erros
                ];
            } elseif (($resultado !== TRUE || count($erros) > 0) && ($qtd_cliente_inserido === 0)) {
                $resposta = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                    'mensagem' => 'Nenhum dado foi cadastrado',
                    'data' => $erros
                ];
            } else {
                $resposta = [
                    'code' => HTTP_OK,
                    'status' => 'sucesso',
                    'data' => $retorno
                ];
            }

            mensagem:
            return response()->json($resposta, $resposta["code"]);
        } catch (Exception $ex) {
            return response()->json(['erro' => true, 'message' => $ex->getMessage()]);
        }
    }

    private function getIdInterno($item)
    {
        $registro = $this->model::select("id")
            ->where([
                "id_filial" => $item["cliente"]["id_filial"],
                "cnpj" => $item["cliente"]["cnpj"],
            ])
            ->first();

        return $registro;
    }

    private function inserirCliente($dado)
    {
        $json = json_decode(json_encode($dado));

        $cliente = new $this->model;
        
        foreach ($json as $key => $value){
            $cliente->{$key} = $value;
        }

        if ($cliente->save()) {
            // commitar o cliente antes de inserir dados que dependam do seu id
            DB::commit();
            $retorno = $cliente->id;
        } else {
            $retorno = null;
        }

        return $retorno;
    }

    private function inserirContato($dado)
    {
        $json = json_decode(json_encode($dado));

        $contato = new Contato;
        
        foreach ($json as $key => $value){
            $contato->$key = $value;
        }

        if ($contato->save()) {
            $retorno = true;
        } else {
            $retorno = false;
        }

        return $retorno;
    }

    private function inserirEndereco($dado)
    {
        $json = json_decode(json_encode($dado));

        $endereco = new Endereco;
        $endereco->id_retaguarda = $json->id_retaguarda;
        $endereco->id_cliente = $json->id_cliente;
        $endereco->logradouro = $json->logradouro;
        $endereco->complemento = $json->complemento;
        $endereco->bairro = $json->bairro;
        $endereco->numero = $json->numero;
        $endereco->uf = $json->uf;
        $endereco->referencia = $json->referencia;
        $endereco->tit_cod = $json->tit_cod;
        $endereco->id_cidade = $json->id_cidade;
        $endereco->cep = $json->cep;
        $endereco->sinc_erp = $json->sinc_erp;

        if ($endereco->save()) {
            $retorno = true;
        } else {
            $retorno = false;
        }

        return $retorno;
    }

    private function inserirReferencia($dado)
    {
        $json = json_decode(json_encode($dado));

        $referencia = new Referencia;
        $referencia->sequencia = $json->sequencia;
        $referencia->fornecedor = $json->fornecedor;
        $referencia->telefone = $json->telefone;
        $referencia->desde = $json->desde;
        $referencia->conceito = $json->conceito;
        $referencia->limite = $json->limite;
        $referencia->pontual = $json->pontual;
        $referencia->ultima_fatura_valor = $json->ultima_fatura_valor;
        $referencia->ultima_fatura_data = $json->ultima_fatura_data;
        $referencia->maior_fatura_valor = $json->maior_fatura_valor;
        $referencia->maior_fatura_data = $json->maior_fatura_data;
        $referencia->maior_acumulo_valor = $json->maior_acumulo_valor;
        $referencia->maior_acumulo_data = $json->maior_acumulo_data;

        if ($referencia->save()) {
            $retorno = $referencia->id;
        } else {
            $retorno = false;
        }

        return $retorno;
    }

    private function inserirClienteReferencia($dado)
    {
        $json = json_decode(json_encode($dado));

        $clienteReferencia = new ClienteReferencia;
        $clienteReferencia->id_cliente = $json->id_cliente;
        $clienteReferencia->id_referencia = $json->id_referencia;

        if ($clienteReferencia->save()) {
            $retorno = true;
        } else {
            $retorno = false;
        }

        return $retorno;
    }
}
