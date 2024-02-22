<?php

namespace App\Services\Relatorios;

use App\Models\Tenant\Pedido as TenantPedido;
use App\Models\Tenant\PedidoItem;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

class Pedido
{
    private $service;
    private $query;
    function __construct()
    {
        $this->service = new BaseService();
    }

    public function assembleReport($request)
    {
        $erro = [];
        try {
            $this->service->verificarCamposRequest($request, RULE_RELATORIO_PEDIDO);
        } catch (Exception $ex) {
            $erro = ['error' => true, 'message' => $ex->getMessage()];
        } finally {
            $registroscount = 0;
            $registrosDePedidos['head'] = [];

            $registrosDePedidos['pedidos'] = TenantPedido::select(
                "pedido.id",
                "pedido.id_retaguarda as pedidoIdRetaguarda",
                "pedido.id_pedido_dispositivo as pedidoIdDispositivo",
                DB::raw("CAST(pedido.valorTotalComImpostos as decimal(15,2)) as valorTotal"),
                DB::raw("CAST(pedido.valor_st as decimal(15,2)) as valorSt"),
                DB::raw("CAST(pedido.valor_frete as decimal(15,2)) as valorFrete"),
                DB::raw("CAST(pedido.valor_seguro as decimal(15,2)) as valorSeguro"),
                DB::raw("CAST(pedido.valor_ipi as decimal(15,2))as valorIpi"),
                "pedido.dt_inicial as dtInicial",
                "pedido.dt_emissao as dtEmissao",
                "pedido.dt_sinc_erp as dtSincErp",
                DB::raw("IF(pedido.pes_bru IS NULL,'0,00',CAST(pedido.pes_bru as decimal(15,2))) AS pesoBruto"),
                DB::raw("IF(pedido.pes_liq IS NULL,'0,00',CAST(pedido.pes_liq as decimal(15,2))) AS pesoLiquido"),
                "pedido.qtde_itens as qtdeItens",
                "pedido.ip",
                "pedido.valor_desconto as pedidoValorDesconto",
                "pedido.observacao",
                DB::raw("DATE_FORMAT( pedido.previsao_entrega,'%d/%m/%Y') as previsaoEntrega"),
                "protabela_preco.tab_desc as tabelaPreco",
                "tipo_pedido.descricao as tipoPedido",
                "prazo_pagamento.descricao as prazoPagamento",
                "forma_pagamento.descricao as formaPagamento",
                "filial.emp_raz as filialRazaoSocial",
                "filial.emp_fan as filialNomeFantasia",
                DB::raw("CAST(pedido.valorVerba as decimal(15,2))as valorVerba"),
                "vendedor.nome as vendedor",
                "vendedor.id as vendedorId",
                "cliente.razao_social as clienteRazaoSocial",
                "cliente.nome_fantasia as clienteNomeFantasia",
                DB::raw("IF(pedido.status=0,IF(pedido.id_retaguarda IS NULL,'Aguardando sincronismo','Sincronizado'),
                CASE(pedido.status)
                WHEN '0' THEN 'Aguardando sincronismo'
                WHEN '1' THEN 'Sincronizado'
                WHEN '5' THEN 'Pendente Aprovação'
                WHEN '6' THEN 'Aprovado'
                WHEN '7' THEN 'Pendente Reprovados'
                WHEN '10' THEN 'Aguardando Pagamento (PGS)'
                WHEN '11' THEN 'Pagamento Confirmado (PGS)'
                WHEN '13' THEN 'Pagamento Negado (PGS)' END) as status
                ")
            )
                ->where(function ($query) use ($request) {
                    if (!is_null($request->idPedido)) $query->whereIn("pedido.id", $request->idPedido);
                    if (!is_null($request->idCliente)) $query->whereIn("cliente.id", $request->idCliente);
                    if (!is_null($request->idPrazoPgto)) $query->whereIn("prazo_pagamento.id", $request->idPrazoPgto);
                    if (!is_null($request->idFormaPgto)) $query->whereIn("forma_pagamento.id", $request->idFormaPgto);
                    if (!is_null($request->idTabelaPreco)) $query->whereIn("protabela_preco.id", $request->idTabelaPreco);
                    if (!is_null($request->idFilial)) $query->whereIn("pedido.id_filial", $request->idFilial);
                    if (!is_null($request->idVendedor)) $query->whereIn("pedido.id_vendedor", $request->idVendedor);
                    if (!is_null($request->status)) $query->whereIn("pedido.status", $request->status);
                    if (!is_null($request->idTipoPedido)) $query->whereIn("tipo_pedido.id", $request->idTipoPedido);
                    if (!is_null($request->dataInicial) && !is_null($request->dataFinal)) $query->whereBetween("pedido.dt_emissao", [$request->dataInicial, $request->dataFinal]);
                })
                ->join("protabela_preco", "pedido.id_tabela", "=", "protabela_preco.id")
                ->join("prazo_pagamento", "pedido.id_prazo_pgto", "=", "prazo_pagamento.id")
                ->join("forma_pagamento", "pedido.id_forma_pgto", "=", "forma_pagamento.id", "left")
                ->join("tipo_pedido", "pedido.id_tipo_pedido", "=", "tipo_pedido.id", "left")
                ->join("vendedor", "pedido.id_vendedor", "=", "vendedor.id")
                ->join("filial", "pedido.id_filial", "=", "filial.id")
                ->join("cliente", "pedido.id_cliente", "=", "cliente.id")
                ->orderBy("pedido.dt_emissao")
                ->paginate($request->paginate);

            if (count($registrosDePedidos['pedidos']) > 0 && $request->exibirItem == 1) {

                $registroscount = count($registrosDePedidos['pedidos']) > 0 ? count($registrosDePedidos['pedidos']) - 1 : count($registrosDePedidos['pedidos']);

                for ($i = 0; $i <= $registroscount; $i++) {

                    $itens = [];

                    $pedidosItens = $request->pedidoComImagem === 1 ? $this->montarQueryComImagem($registrosDePedidos, $i) : $this->montarQuerySemImagem($registrosDePedidos, $i);

                    foreach ($pedidosItens as $item) {
                        array_push($itens, $item);
                        unset($item);
                    }
                    $registrosDePedidos['pedidos'][$i]['itens'] = $itens;
                    $totais = $this->totaisDoPedido($this->query);
                    $registrosDePedidos['pedidos'][$i]['totPedidoItens'] = $totais;

                    unset($itens);
                }

                $registrosDePedidos['head'] = $this->getHead($registrosDePedidos['pedidos']->toArray());
            }

            $registrosDePedidos['error'] = [];
            $registrosDePedidos['message'] = [];
            if (!empty($erro)) {
                $registrosDePedidos["error"] = $erro["error"];
                $registrosDePedidos["message"] = $erro["message"];
            }
            
            return $this->service->verificarErro($registrosDePedidos);
        }
    }

    public function getHead($dados)
    {
        $totIPI = 0;
        $totSt = 0;
        $totFrete = 0;
        $totSeguro = 0;
        $totVerba = 0;
        $totPesoBruto = 0;
        $totPesoLiquido = 0;
        $totValor = 0;
        $totSincronizados = 0;
        $totPendendeAprovacao = 0;
        $totAprovado = 0;
        $totReprovado = 0;
        $totAguardandoSincronismo = 0;

        for ($i = 0; $i < count($dados['data']); $i++) {

            $totIPI += $dados['data'][$i]['valorIpi'];
            $totSt += $dados['data'][$i]['valorSt'];
            $totFrete += $dados['data'][$i]['valorFrete'];
            $totSeguro += $dados['data'][$i]['valorSeguro'];
            $totVerba += $dados['data'][$i]['valorVerba'];
            $totPesoBruto += $dados['data'][$i]['pesoBruto'];
            $totPesoLiquido += $dados['data'][$i]['pesoLiquido'];
            $totValor += $dados['data'][$i]['valorTotal'];

            switch ($dados['data'][$i]['status']) {
                case 'Sincronizado':
                    $totSincronizados++;
                    break;
                case 'Pendente Aprovação':
                    $totPendendeAprovacao++;
                    break;
                case 'Aprovado':
                    $totAprovado++;
                    break;
                case 'Reprovados':
                    $totReprovado++;
                    break;
                default:
                    $totAguardandoSincronismo++;
                    break;
            }
        }

        return [
            'totIpi' => $totIPI,
            'totST' => $totSt,
            'totFrete' => $totFrete,
            'totSeguro' => $totSeguro,
            'totVerba' => $totVerba,
            'totPesoBruto' => $totPesoBruto,
            'totPesoLiquido' => $totPesoLiquido,
            'totValor' => $totValor,
            'totSincronizados' => $totSincronizados,
            'totPendenteAprovacao' => $totPendendeAprovacao,
            'totAprovados' => $totAprovado,
            'totReprovados' => $totReprovado,
            'totAguardandoSincronismo' => $totAguardandoSincronismo,
            'totPedidos' => count($dados['data'])
        ];
    }

    public function montarQueryComImagem($registrosDePedidos, $posicao)
    {

        $this->query = PedidoItem::select(
            DB::raw("CONCAT(produto.id_retaguarda,'/',LOWER(produto.descricao)) as descricaoProduto"),
            "pedido_item.id_pedido as idPedido",
            "pedido_item.numero_item as numeroItem",
            "pedido_item.unidvenda",
            "pedido_item.quantidade",
            "pedido_item.embalagem",
            "pedido_item.id_produto as idProduto",
            "produto_imagem.url",
            DB::raw("format(pedido_item.valorVerba * pedido_item.quantidade,2,'de_DE') as valorVerba"),
            DB::raw("format(pedido_item.valor_st,2,'de_DE') as valorSt"),
            DB::raw("format(pedido_item.valor_ipi,2,'de_DE') as valorIpi"),
            DB::raw("format(pedido_item.valor_frete,2,'de_DE') as valorFrete"),
            DB::raw("format(pedido_item.valor_seguro,2,'de_DE') as valorSeguro"),
            DB::raw("format(pedido_item.percentualdesconto,2,'de_DE') as percentualDesconto"),
            DB::raw("format(pedido_item.valor_unitario,2,'de_DE') as valorUnitario"),
            DB::raw("format(pedido_item.valorTotalComImpostos,2,'de_DE') as valorTotal"),
            "pedido_item.pes_bru as pesBru",
            "pedido_item.pes_liq as pesLiq"
        )
            ->where("pedido_item.id_pedido", $registrosDePedidos['pedidos'][$posicao]['id'])
            ->join("produto", "pedido_item.id_produto", "=", "produto.id")
            ->join("produto_imagem", "produto.id", "=", "produto_imagem.id_produto")
            ->get();

        return $this->query;
    }

    public function montarQuerySemImagem($registrosDePedidos, $posicao)
    {
        $this->query = PedidoItem::select(
            DB::raw("CONCAT(produto.id_retaguarda,'/',LOWER(produto.descricao)) as descricaoProduto"),
            "pedido_item.id_pedido as idPedido",
            "pedido_item.numero_item as numeroItem",
            "pedido_item.unidvenda",
            "pedido_item.quantidade",
            "pedido_item.embalagem",
            "pedido_item.id_produto as idProduto",
            DB::raw("pedido_item.valorVerba * pedido_item.quantidade as valorVerba"),
            "pedido_item.valor_st as valorSt",
            "pedido_item.valor_ipi as valorIpi",
            "pedido_item.valor_frete as valorFrete",
            "pedido_item.valor_seguro as valorSeguro",
            "pedido_item.percentualdesconto as percentualDesconto",
            "pedido_item.valor_unitario as valorUnitario",
            "pedido_item.valorTotalComImpostos as valorTotal",
            "pedido_item.pes_bru as pesBru",
            "pedido_item.pes_liq as pesLiq"
        )
            ->where("pedido_item.id_pedido", $registrosDePedidos['pedidos'][$posicao]['id'])
            ->join("produto", "pedido_item.id_produto", "=", "produto.id")
            ->get();

        return $this->query;
    }

    public function totaisDoPedido($itensPedido)
    {
        $totUnitario = 0;
        $totQtd = 0.0;
        $totIpi = 0.0;
        $totSt = 0.0;
        $totFrete = 0.0;
        $totSeguro = 0.0;
        $totVerba = 0.0;
        $totDesconto = 0.0;
        $totPesBru = 0.0;
        $totPesLiq = 0.0;
        $totValor = 0.0;

        foreach ($itensPedido as $item) {
            $totUnitario += $item['valorUnitario'];
            $totQtd += $item['quantidade'];
            $totIpi += $item['valorIpi'];
            $totSt += $item['valorSt'];
            $totFrete += $item['valorFrete'];
            $totSeguro += $item['valorSeguro'];
            $totVerba += $item['valorVerba'];
            $totDesconto += $item['pedidoValorDesconto'];
            $totPesBru += $item['pesBru'];
            $totPesLiq += $item['pesLiq'];
            $totValor += $item['valorTotal'];
        }

        return [
            'totUnitario' => $totUnitario,
            'totQtd' => $totQtd,
            'totIpi' => $totIpi,
            'totSt' => $totSt,
            'totFrete' => $totFrete,
            'totSeguro' => $totSeguro,
            'totVerba' => $totVerba,
            'totDesconto' => $totDesconto,
            'totPesBru' => $totPesBru,
            'totPesLiq' => $totPesLiq,
            'totValor' => $totValor
        ];
    }
}
