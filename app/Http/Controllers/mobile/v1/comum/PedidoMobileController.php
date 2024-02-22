<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Central\PedidosNovosEmail;
use App\Models\Tenant\Pedido;
use App\Models\Tenant\PedidoItem;
use App\Models\Tenant\Vendedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoMobileController extends BaseMobileController
{
    protected $className;

    public function __construct()
    {
        $this->className = "Pedido";
        $this->model = Pedido::class;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    public function pedidoatualizado(Request $request)
    {
        try {

            $array = $request->json;
            $id_vendedor = $request->id_vendedor;
            $retorno = [];

            //VERIFICA SE O JSON É INVÁLIDO
            if (is_null($array)) {
                $resposta = $this->retorna_msg_erro(ERRO_JSON_INVALIDO, "", $array, "json");
                goto mensagem;
            }

            foreach ($array as $item) {
                $bd = $this->model::select(
                    "id_retaguarda",
                    "dt_sinc_erp",
                    "status"
                )
                    ->where([
                        "id_pedido_dispositivo" => intval($item["id"]),
                        "id_vendedor" => $id_vendedor
                    ])
                    ->first();

                if (isset($bd)) {
                    $retorno[] = [
                        "id" => intval($item["id"]),
                        "id_retaguarda" => $bd["id_retaguarda"],
                        "dt_sinc_erp" => date("Y-m-d H:i:s", strtotime($bd["dt_sinc_erp"]))
                    ];
                } else {
                    $retorno[] = [
                        "id" => intval($item["id"]),
                        "error" => "Pedido não existe ou id incorreto."
                    ];
                }
            }

            $resposta = [
                'code' => HTTP_OK,
                'status' => 'sucesso',
                'data' => $retorno
            ];

            mensagem:
            return response()->json($resposta, $resposta["code"]);
        } catch (Exception $ex) {
            return response()->json(['erro' => true, 'message' => $ex->getMessage()]);
        }
    }

    public function pedidojson(Request $request)
    {
        try {
            $array = $request->json;
            $ven_cod = $request->id_vendedor;
            $sequencia_pedido = $request->sequencia_pedido;
            $ip = $this->service->getIp();

            if ($sequencia_pedido === NULL || $sequencia_pedido === "") {
                $resposta = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                    'mensagem' => "Informe a sequencia do pedido"
                ];
                goto mensagem;
            }

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
                $resposta = $this->retorna_msg_erro(ERRO_JSON_INVALIDO, "", $array, "json");
                goto mensagem;
            }

            $this->atualizarVendedor(intval($ven_cod), ["sequencia_pedido" => $sequencia_pedido]);

            $erros = NULL;
            $retorno = NULL;
            $qtd_pedido_inserido = 0;
            $id_interno_pedido = 0;
            $id_pedido_mobile = 0;
            $validacao_itens = [];
            $resultado = [];
            $i = NULL;

            // LÊ TODOS OS DADOS DE CADA ARRAY DENTRO DO JSON
            foreach ($array as $pedido_completo) {
                $pedido_completo["pedido"]["id_vendedor"] = $ven_cod; // atribui o id do vendedor dentro de um novo parametro no array
                $id_pedido_mobile = $pedido_completo['pedido']['id']; // armazena o id do dispositivo em uma variavel
                $pedido_completo["pedido"]["id_pedido_dispositivo"] = strval($pedido_completo['pedido']['id']); // atribui o valor do id ao novo parametro
                unset($pedido_completo['pedido']['id']); //apaga o parametro vindo do array

                $pedido_completo["pedido"]["origem"] = ORIGEM_PEDIDO_AFV;
                $pedido_completo["pedido"]["mac"] = $this->mac;

                $this->service->transacao("abrir");

                // $validacao_pedido = $this->service->verificarCamposRequest($pedido_completo["pedido"], RULE_VISITA_SERVICO, null, null, 'return');
                $validacao_pedido = $this->validacao_pedido($pedido_completo["pedido"]);

                //Valida item a item, caso não passe na validação, retorna o erro e não deixa inserir
                foreach ($pedido_completo["itens"] as &$validar_por_itens) {
                    $resposta_validacao_itens = $this->validacao_pedido_item($validar_por_itens);

                    if (isset($resposta_validacao_itens[0])) { // verifica se houve erro na validação
                        $i++;
                        $erros[]["erro_itens"] = $resposta_validacao_itens; // cria um array de erros da validação do item
                    }
                }

                if (!isset($validacao_pedido[0]) && $i === NULL) {
                    $i = NULL;
                    $pedido_completo["pedido"]["ip"] = &$ip;

                    $existe = $this->verificaPedidoExistente($id_pedido_mobile, $ven_cod);
                    //se existir registro ele atualiza
                    if ($existe != NULL) {
                        $id_interno_pedido = $this->atualizaPedidoIdDispositivo($pedido_completo["pedido"], $id_pedido_mobile, $ven_cod, $existe, $this->mac, NULL); // INSERSÃO DO PEDIDO
                    } else { // senão existir insere
                        $pedido_completo["pedido"]["id_retaguarda"] = "AGUARDANDO_ITEM";

                        $id_interno_pedido = $this->inserirPedidoComLog($pedido_completo["pedido"]); // INSERSÃO DO PEDIDO
                    }

                    if (($id_interno_pedido <= 0) || ($id_interno_pedido === NULL)) {
                        //unset($pedido_completo["pedido"]["notificacao_afv_manager"]);
                        $erros[] = ['erro' => 'falha ao inserir PEDIDO', 'recebido' => $pedido_completo["cliente"]];
                    } else {

                        //verifica se o vendedor marcou para enviar e-mail
                        if ($pedido_completo["pedido"]["enviarEmail"] == 1) {
                            //Salva no banco de dados CENTRAL o ID do pedido mais o id da empresa para o serviço enviar os emails
                            $this->pedidoEnvioEmail($id_interno_pedido);
                        }

                        $arrayDeNumeroItem = [];
                        $ultimoNumeroItem = $pedido_completo["itens"][count($pedido_completo["itens"]) - 1]["numero_item"];

                        //INSERSÃO ITENS
                        foreach ($pedido_completo["itens"] as $itens) {
                            unset($itens["id"]);
                            $itens["id_pedido"] = $id_interno_pedido;

                            if (in_array($itens["numero_item"], $arrayDeNumeroItem)) $itens["numero_item"] = $ultimoNumeroItem + 1;
                            array_push($arrayDeNumeroItem, $itens["numero_item"]);

                            $inserido_itens = $this->inserirItemPedido($itens);

                            if ($inserido_itens !== TRUE) {
                                $erros[] = ['erro' => 'falha ao inserir ITENS', 'recebido' => $itens];
                            }
                        }

                        $this->atualizaIdRetaguardaSeAguardandoItem(["id_retaguarda" => NULL], $id_interno_pedido);
                    }
                } else {
                    $erros[]["erro_pedido"] = $validacao_pedido;
                }

                if (isset($erros)) {
                    $resultado = false;
                    $idPedido = isset($pedido_completo["pedido"]["id"]) ? $pedido_completo["pedido"]["id"] : $pedido_completo["pedido"]["id_pedido_dispositivo"];
                    $erros[]["nao_inserido"] = ['erro' => 'falha ao inserir todos os dados do Pedido (' . $idPedido . ")", 'recebido' => $pedido_completo];
                } else {
                    $this->service->transacao("fechar");
                    $resultado = true;
                    //Vai atribuindo os IDs dos clientes cadastrados na nuvem e retona para o dispositivo
                    $retorno[] = [
                        "id_mobile" => $id_pedido_mobile,
                        "id_nuvem" => $id_interno_pedido,
                    ];
                    $qtd_pedido_inserido++; // conta quantos pedidos foram cadastrados
                }
            }

            if (($resultado !== TRUE || isset($erros)) && ($qtd_pedido_inserido > 0)) {
                $resultado = [
                    'code' => HTTP_NOT_ACCEPTABLE,
                    'status' => 'sucesso_parcial',
                    'mensagem' => 'Alguns dados nao foram inseridos',
                    'data' => $erros
                ];
            } elseif (($resultado !== TRUE || isset($erros)) && ($qtd_pedido_inserido === 0)) {
                $resultado = [
                    'code' => HTTP_NOT_FOUND,
                    'status' => 'error',
                    'mensagem' => 'Nenhum dado foi cadastrado',
                    'data' => $erros
                ];
            } else {
                $resultado = [
                    'code' => HTTP_OK,
                    'status' => 'sucesso',
                    'data' => $retorno
                ];
            }

            mensagem:
            return response()->json($resultado, $resultado["code"]);
        } catch (Exception $ex) {
            return response()->json(['erro' => true, 'message' => $ex->getMessage()]);
        }
    }


    public function atualizaIdRetaguardaSeAguardandoItem($dados, $idInterno)
    {
        $pedido = $this->model::where(
            [
                "id" => $idInterno,
                "id_retaguarda" => "AGUARDANDO_ITEM"
            ]
        )->first();



        if (!isset($pedido)) {
            $retorno = 0;
        } else {

            foreach ($dados as $coluna => $valor) {
                $pedido->{$coluna} = $valor;
            }
            if ($pedido->update()) {
                $retorno = $idInterno;
            } else {
                $retorno = 0;
            }
        }

        $mensagem = "falha_atualizar";

        // Área de Log
        if ($retorno !== NULL && $retorno !== 0) { //Se não houve nenhum erro
            $data["id"] = $retorno;
            $mensagem = "atualizado";
        }
        $this->service->_salvar_log(LOG_ATUALIZACAO, $this->className, serialize($dados), $mensagem, $this->usuarioLogado()->fk_empresa, NULL, $this->mac);
        return $retorno;
    }

    private function pedidoEnvioEmail($idPedido)
    {
        $registro = new PedidosNovosEmail;
        $registro->id_pedido = $idPedido;
        $registro->fk_empresa = $this->usuarioLogado()->fk_empresa;
        $registro->save();
    }

    private function inserirItemPedido($dados)
    {
        $where =  [
            "id_pedido" => $dados["id_pedido"],
            "numero_item" => $dados["numero_item"]
        ];

        $mensagem = "falha_inserir";

        DB::connection($this->service->connection('driver'))->table(TABELA_PEDIDO_ITEM)->where($where)->delete();

        $itemPedido = new PedidoItem;

        foreach ($dados as $coluna => $valor) {
            $itemPedido->{$coluna} = $valor;
        }

        // Área de Log
        if ($itemPedido->save()) { //Se não houve nenhum erro
            $data["id"] = $itemPedido->id;
            $mensagem = "inserido";
            $resposta = TRUE;
        } else {
            $data["id"] = $dados["id_pedido"];
            $resposta = FALSE;
        }

        $this->service->_salvar_log(LOG_NOVO, "pedido_item", serialize($dados), $mensagem, NULL, NULL, $this->mac);

        return $resposta;
    }

    private function inserirPedidoComLog($dados)
    {
        $pedido = new $this->model;

        $mensagem = "falha_inserir";

        foreach ($dados as $coluna => $valor) {
            $pedido->{$coluna} = $valor;
        }

        // Área de Log
        if ($pedido->save()) { //Se não houve nenhum erro
            $data["id"] = $pedido->id;
            $mensagem = "inserido";
            $resposta = $pedido->id;
        } else {
            $data["id"] = $dados["id_pedido_dispositivo"];
            $resposta = NULL;
        }

        $this->service->_salvar_log(LOG_NOVO, $this->className, serialize($data), $mensagem, NULL, NULL, $this->mac);

        return $resposta;
    }

    private function atualizaPedidoIdDispositivo($pedidoNewMobile, $idPedidoMobile, $idVendedor, $pedidoExistente)
    {
        $pedido = $this->model::where([
            'id_pedido_dispositivo' => $idPedidoMobile,
            "id_vendedor" => $idVendedor
        ])->first();

        foreach ($pedidoNewMobile as $coluna => $valor) {
            $pedido->{$coluna} = $valor;
        }

        if ($pedido->update()) {
            $resposta = $pedido->id;
        } else {
            $resposta = NULL;
        }

        return $resposta;
    }

    private function verificaPedidoExistente($idPedidoMobile, $idVendedor)
    {
        $pedido = $this->model::where([
            "id_pedido_dispositivo" => $idPedidoMobile,
            "id_vendedor" => $idVendedor
        ])->first();

        if (isset($pedido)) {
            $resposta = $pedido;
        } else {
            $resposta = NULL;
        }

        return $resposta;
    }

    private function atualizarVendedor($idVendedor, $dados)
    {
        $vendedor = Vendedor::find($idVendedor);

        foreach ($dados as $coluna => $valor) {
            $vendedor->{$coluna} = $valor;
        }

        $vendedor->update();
    }

    private function validacao_pedido(&$dados)
    {
        $campos = array(
            "id_filial", "id_endereco", "id_cliente", "id_pedido_dispositivo", "id_tabela", "id_vendedor",
            "id_prazo_pgto", "id_forma_pgto", "supervisor", "gerente", "id_tipo_pedido", "origem", "valor_total",
            "qtde_itens", "status", "observacao", "valor_st", "valor_frete", "valor_seguro", "valor_ipi", "observacao_cliente", "pedido_original", "valor_acrescimo",
            "valor_desconto", "dt_emissao", "previsao_entrega", "dt_inicial", "valorTotalComImpostos", "valorTotalBruto",
            "enviarEmail", "latitude", "longitude", "precisao", "valorVerba", "bonificacaoPorVerba", "tipo_frete", "mac", "autorizacaoDataEnviada", "autorizacaoSenha",
            "autorizacaoaDataProcessada", "distanciaCliente", "motivoBloqueio", "id_pedido_cliente"
        );

        /**Verifica se os parametros passado via POST existe no array criado se não tiver dentro do array campos ele
         * exclue o parametro para não dar erro no banco de dados de insersão*/
        foreach ($dados as $chave => $valor) {
            if (!in_array($chave, $campos)) {
                unset($dados[$chave]);
            }
        }

        if (isset($dados["observacao"])) {
            $quebraDeLinha = array("\r\n", "\n", "\r");
            $aspas = array("'", "\"");
            $dados["observacao"] = str_replace($quebraDeLinha, " ", $dados["observacao"]);
            $dados["observacao"] = str_replace($aspas, "", $dados["observacao"]);
        }

        $validacao = $this->service->verificarCamposRequest($dados, RULE_PEDIDO_CABECALHO_SERVICO, null, null, 'return');

        return $validacao;
    }

    private function validacao_pedido_item(&$dados)
    {
        $campos = array(
            "id_pedido", "numero_item", "id_produto", "id_tabela", "embalagem", "unidvenda", "quantidade", "status", "valor_total",
            "valor_st", "valor_tabela", "valor_desconto", "valor_frete", "valor_seguro", "valor_unitario", "valor_ipi", "base_st", "observacao",
            "percentualDesconto", "tipoAcrescimoDesconto", "ped_desqtd", "valorVerba", "valorTotalComImpostos", "percentualVerba", "pes_bru", "pes_liq"
        );

        /**Verifica se os parametros passado via POST existe no array criado se não tiver dentro do array campos ele
         * exclue o parametro para não dar erro no banco de dados de insersão*/
        foreach ($dados as $chave => $valor) {
            if (!in_array($chave, $campos)) {
                unset($dados[$chave]);
            }
        }

        $validacao = $this->service->verificarCamposRequest($dados, RULE_PEDIDO_ITENS_SERVICO, null, null, 'return');

        return $validacao;
    }
}
