<?php

namespace App\Http\Controllers\servico\v1\ERP;

use App\Http\Controllers\servico\v1\BaseServicoController;
use App\Models\Central\TotalizadorEmpresa;
use App\Models\Tenant\Pedido;
use App\Models\Tenant\PedidoItem;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PedidoServicoController extends BaseServicoController
{
    public function __construct(BaseService $service)
    {
        $this->nomeCamposDb = self::_nomeCamposDb();
        $this->tabela = TABELA_PEDIDO;
        parent::__construct($this, $this->nomeCamposDb, $this->tabela);
        $this->filha = $this;
        $this->metodo = 'Pedido' . CLASS_SERVICE;
        $this->entity = PedidoServicoController::class;
        $this->firstOrNew = ["fk_empresa"];
        $this->acaoTabela = 0;
        $this->model = Pedido::class;
        $this->customCasts = [
            "cabecalhoPedido" => [
                "id", "idEnd", "status_pedido", "tipo_frete",
                "id_cliente_interno", "bonificacaoPorVerba"
            ],
            "itensPedido" => [
                "id_pedido", "numero_item"
            ]
        ];
        $this->idEmpresa = $service->usuarioLogado()->fk_empresa;
    }

    /**
     * Retorna a lista dos novos pedidos
     */
    public function getPedidos()
    {
        $limit = random_int(1, 15);
        $dados = [];

        $data = $this->model::withCasts($this->util->convertValueJSON($this->model, $this->customCasts["cabecalhoPedido"], true))->select(
            "pedido.id",
            "pedido.mac",
            "pedido.id_filial",
            "pedido.id_retaguarda",
            DB::raw("(CASE
            WHEN " . NOME_BANCO_CENTRAL . ".SPLIT_STR( pedido.id_endereco,'.',4)
             THEN " . NOME_BANCO_CENTRAL . ".SPLIT_STR( pedido.id_endereco,'.',3)
            ELSE " . NOME_BANCO_CENTRAL . ".SPLIT_STR( pedido.id_endereco,'.',4)
            END) AS idEnd"),
            "pedido.id_vendedor",
            DB::raw("vendedor.nome as nome_vendedor"),
            "pedido.supervisor",
            "pedido.gerente",
            "pedido.id_pedido_dispositivo",
            "pedido.id_pedido_cliente",
            "pedido.origem",
            "pedido.valor_total",
            "pedido.qtde_itens",
            "pedido.previsao_entrega",
            "pedido.dt_entrega",
            "pedido.nota_fiscal",
            "pedido.status_entrega",
            DB::raw("pedido.status AS status_pedido"),
            "pedido.placa",
            DB::raw("SUBSTRING(REPLACE(pedido.observacao,\"\"\"\",''),1,254) AS observacao"),
            "pedido.valor_st",
            "pedido.valor_ipi",
            "pedido.valor_frete",
            "pedido.valor_seguro",
            DB::raw("SUBSTRING(REPLACE(pedido.observacao_cliente,\"\"\"\",''),1,254) AS observacao_cliente"),
            "pedido.valor_acrescimo",
            "pedido.valor_desconto",
            "pedido.valor_frete",
            "pedido.ip",
            "pedido.dt_emissao",
            "pedido.dt_cadastro",
            "pedido.dt_inicial",
            "pedido.valorTotalBruto",
            "pedido.valorTotalComImpostos",
            "pedido.nfs_num",
            "pedido.bonificacaoPorVerba",
            "pedido.tipo_frete",

            DB::raw("cliente.id_retaguarda as id_cliente"),
            DB::raw("cliente.id as id_cliente_interno"),

            DB::raw("protabela_preco.id_retaguarda as id_protabela_preco"),

            DB::raw("prazo_pagamento.id_retaguarda as id_prazo_pagamento"),
            DB::raw("prazo_pagamento.descricao as descricao_prazo_pagamento"),

            DB::raw("forma_pagamento.id_retaguarda as id_forma_pagamento"),
            DB::raw("forma_pagamento.descricao as descricao_forma_pagamento"),

            DB::raw("tipo_pedido.id_retaguarda as tipo_pedido"),

            DB::raw("(CASE
            WHEN (configuracao_filial.valor = 1 AND pedido.origem = 'P') THEN '1'
            WHEN (configuracao_filial.valor = 2 AND pedido.origem = 'W') THEN '1'
            WHEN configuracao_filial.valor = 3 THEN '1'
            ELSE 0
            END) as libera_automatico")
        )
            ->limit($limit);

        $data->join("cliente", "pedido.id_cliente", "=", "cliente.id")
            ->join("protabela_preco", "pedido.id_tabela", "=", "protabela_preco.id")
            ->join("prazo_pagamento", "pedido.id_prazo_pgto", "=", "prazo_pagamento.id")
            ->join("forma_pagamento", "pedido.id_forma_pgto", "=", "forma_pagamento.id")
            ->join("tipo_pedido", "pedido.id_tipo_pedido", "=", "tipo_pedido.id")
            ->join("vendedor", "pedido.id_vendedor", "=", "vendedor.id")
            ->leftJoin('configuracao_filial', 'pedido.id_filial', '=', 'configuracao_filial.id_filial');

        $STATUS_PEDIDO_PENDENTE_APROVACAO = [5, 7, 10, 12, 13]; // 5 = pendente, 7 = reprovado

        $cabecalho = $data->where("pedido.id_retaguarda", NULL)
            ->whereNotNull("cliente.id_retaguarda")
            ->where("configuracao_filial.descricao", "LIBERACAO_AUTOMATICA")
            ->whereNotIn("pedido.status", $STATUS_PEDIDO_PENDENTE_APROVACAO)
            ->get();

        if ($cabecalho !== NULL) {
            foreach ($cabecalho as $pedido) {

                $itens = $this->getItensPedido($pedido);

                if ($itens === NULL) {
                    continue;
                }

                $qtdItens = count($itens);

                if ($pedido['qtde_itens'] != $qtdItens) {
                    $pedido['qtde_itens'] = (string) $qtdItens;
                }

                $pedido['observacao_cliente'] = $this->_removeQuebrasDeLinha($pedido['observacao_cliente']);
                $pedido['observacao'] = $this->_removeQuebrasDeLinha($pedido['observacao']);
                $pedido["itens"] = $this->getItensPedido($pedido);

                array_push($dados, $pedido);
            }
        }

        $resposta = empty($dados) ? [
            "status" => "erro",
            "code" => HTTP_NOT_FOUND,
            "mensagem" => ERRO_REGISTRO_NAO_LOCALIZADO
        ] : [
            "status" => "sucesso",
            "code" => HTTP_ACCEPTED,
            "data" => $dados
        ];
        return response()->json($resposta, HTTP_CREATED);
    }

    public function servicolocal()
    {
        $vetor = [
            "fk_empresa" => $this->idEmpresa,
            "dt_atualizado" => date("Y-m-d H:i:s")
        ];

        try {
            $concluido = self::atualizarInserir($vetor);

            if ($concluido) {
                $resposta = [
                    "code" => HTTP_OK,
                    "status" => "sucesso"
                ];
            } else {
                $resposta = [
                    "code" => HTTP_INTERNAL_SERVER_ERROR,
                    "status" => "erro",
                    "mensagem" => "Houve um imprevisto ao inserir"
                ];
            }

            return response()->json($resposta, $resposta['code']);
        } catch (Exception $ex) {
            return  $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    private function validaPedidoEmpresa($id_pedido, $interno = FALSE, $verifica = TRUE, $select = NULL)
    {
        $data = $this->model::select("id");

        if ($interno) {
            $data->where("id", $id_pedido);
        } else {
            $data->where("id_retaguarda", $id_pedido);
        }

        if (isset($select)) {
            $data->select($select);
        }

        $query = $data->first();

        if ($verifica) {
            $retorno = isset($query);
        } else {
            $retorno = $query;
        }
        return $retorno;
    }

    /**
     * Atualiza O ID do retaguarda para os novos pedidos
     */
    public function atualizaPedidos(Request $request)
    {
        $resposta = NULL;

        $log = NULL;

        $arrayPedidos = isset($request->json) ? json_decode($request->json, true) : $request->all();
        $resposta = $this->service->_validarArray($arrayPedidos);

        try {
            if (is_array($resposta) != TRUE) {

                foreach ($arrayPedidos as $chave => $valor) {
                    $id_interno = is_array($valor) ? key($valor) : $chave; // ID da nuvem
                    $id_retaguarda = is_array($valor) ? $valor[$id_interno] : $valor; // ID do sistema local

                    $validacao = $this->validaPedidoEmpresa($id_interno, TRUE);

                    if (!$validacao) {
                        $log[$id_interno] = "INVALIDO";
                    } else {
                        $resultado = $this->atualizarIdRetaguarda($id_interno, $id_retaguarda, NULL, $this->idEmpresa);
                        $log[$id_interno] = "FALHA"; // Infoma se ouve Sucesso ou Erro

                        if ($resultado) {
                            $log[$id_interno] = "OK";
                            // $this->load->model("Totalizador_empresa_model");

                            $pedido = $this->validaPedidoEmpresa($id_interno, TRUE, FALSE, "dt_emissao");

                            if (!is_null($pedido)) {
                                $somenteDataEmissao = $this->service->convertStringToDate($pedido->dt_emissao);
                                $somenteDataEmissao = $somenteDataEmissao->format('Y-m-d');

                                $totais = $this->getTotalPedidos($somenteDataEmissao);

                                if (!is_null($totais)) $this->atualizarInserir($totais);
                            }
                        }
                    }
                }

                //retorno para o cliente
                $resposta = (count($log) > 0) ? [
                    "code" => HTTP_ACCEPTED,
                    "status" => "successo",
                    "mensagem" => $log
                ] : [
                    "code" => HTTP_NOT_FOUND,
                    "status" => "error",
                ];
            }

            return response()->json($resposta, $resposta['code']);

        } catch (Exception $ex) {
            return  $this->service->_mensagemExcecao($ex->getMessage(), $ex->getCode());
        }
    }

    public function atualizarIdRetaguarda($id_interno, $id_retaguarda, $mac = NULL, $id_empresa = NULL)
    {
        $data = date("Y-m-d H:i:s");

        $pedido = $this->model::where("id", $id_interno)
            ->first();
        $pedido->id_retaguarda = $id_retaguarda;
        $pedido->notificacao_afv_manager = 2;
        $pedido->dt_sinc_erp = $data;
        $pedido->save();

        $retorno = $pedido->save();
        $mensagem = ($retorno) ? "OK" : "FALHA";
        return $retorno;
    }

    public function _nomeCamposDb()
    {
        return array("fk_empresa", "data", "qtd_pedido", "valor_pedido", "peso_bruto", "peso_liquido");
    }

    public function atualizarInserir($dados)
    {
        return parent::store($dados, TotalizadorEmpresa::class, ["fk_empresa", "data"], self::_nomeCamposDb());
    }

    private function getTotalPedidos($somenteDataEmissao)
    {
        $data = $this->model::select(
            DB::raw("'{$this->idEmpresa}' AS fk_empresa"),
            DB::raw("DATE_FORMAT(pedido.dt_emissao, '%Y-%m-%d') AS data"),
            DB::raw("COUNT(pedido.id) AS qtd_pedido"),
            DB::raw("SUM(pedido.valorTotalComImpostos) AS valor_pedido"),
            DB::raw("SUM(pedido.pes_bru) AS peso_bruto"),
            DB::raw("SUM(pedido.pes_liq) AS peso_liquido")
        )
            ->whereRaw("pedido.dt_emissao LIKE '$somenteDataEmissao%'")
            ->groupBy("pedido.dt_emissao")
            ->first();

        return $data;
    }

    public function getItensPedido($dados)
    {
        $modelPedidoItem = PedidoItem::class;
        $select_item =
            [
                "pedido_item.id_pedido", "pedido_item.numero_item", "pedido_item.embalagem", "pedido_item.unidvenda",
                "pedido_item.ped_desqtd", "pedido_item.quantidade", "pedido_item.status", "pedido_item.valor_total",
                "pedido_item.valor_st", "pedido_item.valor_ipi", "pedido_item.valor_tabela", "pedido_item.valor_unitario",
                "pedido_item.valor_desconto", "pedido_item.valor_frete", "pedido_item.valor_seguro", "pedido_item.base_st",
                "pedido_item.dt_cadastro", "pedido_item.percentualdesconto", "pedido_item.tipoacrescimodesconto",
                DB::raw("protabela_preco.id_retaguarda as id_protabela_preco"), "pedido_item.valorTotalComImpostos",
                DB::raw("produto.id_retaguarda as id_produto"), DB::raw("produto.descricao as descricao_produto")
            ];

        $resultado = $modelPedidoItem::withCasts($this->util->convertValueJSON($modelPedidoItem, $this->customCasts["itensPedido"], true))->select($select_item)
            ->join("produto", "pedido_item.id_produto", "=", "produto.id")
            ->join("protabela_preco", "pedido_item.id_tabela", "=", "protabela_preco.id")
            ->where("pedido_item.id_pedido", $dados['id'])
            ->orderBy("pedido_item.numero_item", "ASC")
            ->get();

        // verifica se encontrou registros
        if ($resultado !== NULL) {
            //atribui alguns valores no array de Itens
            foreach ($resultado as &$linha) {
                $linha["id_vendedor"] = $dados["id_vendedor"];
                $linha["id_filial"] = $dados["id_filial"];
            }
        }

        return $resultado;
    }

    private function _removeQuebrasDeLinha(?string $string): string
    {
        return ($string === null) ? '' : str_replace(array("\n\r", "\n", "\r"), '', $string);
    }
}
