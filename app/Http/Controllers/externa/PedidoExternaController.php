<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\Pedido;
use App\Models\Tenant\PedidoItem;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class PedidoExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = Pedido::class;
        $this->filters = ['id_filial', 'id_retaguarda', 'id_endereco', 'id_cliente', 'id_pedido_dispositivo', 'id_tabela', 'id_vendedor', 'id_prazo_pgto', 'id_forma_pgto', 'id_tipo_pedido'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_PEDIDO_EXTERNA;
        $this->tabela = 'pedido';
        $this->firstOrNew = ['id_filial', 'id_retaguarda', 'id_endereco', 'id_cliente', 'id_pedido_dispositivo', 'id_tabela', 'id_vendedor', 'id_prazo_pgto', 'id_forma_pgto', 'id_tipo_pedido'];
        $this->modelComBarra = "\Pedido";
        $this->fields = [
            "id_filial",
            "id_retaguarda",
            "id_endereco",
            "id_cliente",
            "id_pedido_dispositivo",
            "id_tabela",
            "id_vendedor",
            "id_prazo_pgto",
            "id_forma_pgto",
            "id_tipo_pedido",
            "supervisor",
            "gerente",
            "valor_total",
            "qtde_itens",
            "nota_fiscal",
            "status",
            "status_entrega",
            "placa",
            "valor_st",
            "valor_ipi",
            "valor_acrescimo",
            "valor_desconto",
            "valorTotalComImpostos",
            "valorTotalBruto",
            "valorVerba",
            "bonificacaoPorVerba",
            "valor_frete",
            "valor_seguro",
            "margem",
            "observacao",
            "observacao_cliente",
            "previsao_entrega",
            "pedido_original",
            "latitude",
            "longitude",
            "precisao",
            "dt_entrega",
            "dt_inicial",
            "dt_emissao",
            "dt_sinc_erp",
            "dt_cadastro",
            "origem",
            "notificacao_afv_manager",
            "enviarEmail",
            "ip",
            "mac",
            "autorizacaoDataEnviada",
            "autorizacaoSenha",
            "autorizacaoaDataProcessada",
            "distanciaCliente",
            "motivoBloqueio",
            "pes_bru",
            "pes_liq",
            "nfs_num"
        ];
    }

    public function destroyPedidoExterna(Request $request)
    {
        isset($request->id) ? $where = ['id' => $request->id] : $where = ["id_retaguarda" => $request->idRetaguarda];
        return $this->destroyWhere($where);
    }

    public function getPedidoItem(Request $request)
    {
        $where = ['id' => $request->id];
        $where = Pedido::where($where)
            ->with('pedido_items')
            ->get();
        return $where;
    }
    public function storePedidoExterna(Request $request)
    {
        try {
            $pedido = Pedido::firstOrNew(['id_retaguarda' => $request->idRetaguarda]);
            $pedido->id_filial = $request->idFilial;
            $pedido->id_retaguarda = $request->idRetaguarda;
            $pedido->id_endereco = $request->idEndereco;
            $pedido->id_cliente = $request->idCliente;
            $pedido->id_pedido_dispositivo = $request->idPedidoDispositivo;
            $pedido->id_tabela = $request->idTabela;
            $pedido->id_vendedor = $request->idVendedor;
            $pedido->id_prazo_pgto = $request->idPrazoPgto;
            $pedido->id_forma_pgto = $request->idFormaPgto;
            $pedido->id_tipo_pedido = $request->idTipoPedido;
            $pedido->supervisor = $request->supervisor;
            $pedido->gerente = $request->gerente;
            $pedido->valor_total = $request->valorTotal;
            $pedido->qtde_itens = $request->qtdeItens;
            $pedido->nota_fiscal = $request->notaFiscal;
            $pedido->status = $request->status;
            $pedido->status_entrega = $request->statusEntrega;
            $pedido->placa = $request->placa;
            $pedido->valor_st = $request->valorSt;
            $pedido->valor_ipi = $request->valorIpi;
            $pedido->valor_acrescimo = $request->valorAcrescimo;
            $pedido->valor_desconto = $request->valorDesconto;
            $pedido->valorTotalComImpostos = $request->valorTotalComImpostos;
            $pedido->valorTotalBruto = $request->valorTotaBruto;
            $pedido->valorVerba = $request->valorVerba;
            $pedido->bonificacaoPorVerba = $request->bonificacaoPorVerba;
            $pedido->valor_frete = $request->valorFrete;
            $pedido->valor_seguro = $request->valorSeguro;
            $pedido->margem = $request->margem;
            $pedido->observacao = $request->observacao;
            $pedido->observacao_cliente = $request->observacaoCliente;
            $pedido->previsao_entrega = $request->previsaoEntrega;
            $pedido->pedido_original = $request->pedidoOriginal;
            $pedido->latitude = $request->latitude;
            $pedido->longitude = $request->longitude;
            $pedido->precisao = $request->precisao;
            $pedido->dt_entrega = $request->dtEntrega;
            $pedido->dt_inicial = $request->dtInicial;
            $pedido->dt_emissao = $request->dtEmissao;
            $pedido->dt_sinc_erp = $request->dtCadastro;
            $pedido->dt_cadastro = $request->dtCadastro;
            $pedido->origem = $request->origem;
            $pedido->notificacao_afv_manager = $request->enviarEmail;
            $pedido->enviarEmail = $request->enviarEmail;
            $pedido->ip = $request->ip;
            $pedido->mac = $request->mac;
            $pedido->autorizacaoDataEnviada = $request->autorizacaoDataEnviada;
            $pedido->autorizacaoSenha = $request->autorizacaoSenha;
            $pedido->autorizacaoaDataProcessada = $request->autorizacaoDataProcessada;
            $pedido->distanciaCliente = $request->distanciaCliente;
            $pedido->motivoBloqueio = $request->motivoBloqueio;
            $pedido->pes_bru = $request->pes_bru;
            $pedido->pes_liq = $request->pesLiq;
            $pedido->nfs_num = $request->nfsNum;
            $pedido->save();
            if ($pedido->save()) {
                $this->modelComBarra="\PedidoItem";
                $this->tabela="pedido_item";
                $insert=0;
                $update=0;
                foreach ($request->itens as $item) {
                    $item['idPedido'] = $request->idPedido;
                    $this->service->verificarCamposRequest($item,RULE_PEDIDO_ITEM_EXTERNA);
                    $where=['id_pedido' => $request->idPedido,'numero_item' => $item['numeroItem']];
                    $this->destroyWhere($where);
                    $itemPedido = PedidoItem::firstOrNew(['id_pedido' => $request->idPedido,'numero_item' => $item['numeroItem']]);
                    $itemPedido->id_pedido = $item['idPedido'];
                    $itemPedido->numero_item = $item['numeroItem'];
                    $itemPedido->id_produto = $item['idProduto'];
                    $itemPedido->id_tabela = $item['idTabela'];
                    $itemPedido->embalagem = $item['embalagem'];
                    $itemPedido->quantidade = $item['quantidade'];
                    $itemPedido->valor_total = $item['valorTotal'];
                    $itemPedido->valor_st = $item['valorSt'];
                    $itemPedido->valor_ipi = $item['valorIpi'];
                    $itemPedido->valor_tabela = $item['valorTabela'];
                    $itemPedido->valor_unitario = $item['valorUnitario'];
                    $itemPedido->valor_desconto = $item['valorDesconto'];
                    $itemPedido->cashback = $item['cashback'];
                    $itemPedido->unitario_cashback = $item['unitarioCashback'];
                    $itemPedido->valor_frete = $item['valorFrete'];
                    $itemPedido->valor_seguro = $item['valorSeguro'];
                    $itemPedido->valorVerba = $item['valorVerba'];
                    $itemPedido->valorTotalComImpostos = $item['valorTotalComImpostos'];
                    $itemPedido->valor_icms = $item['valorIcms'];
                    $itemPedido->ped_desqtd = $item['pedDesqtd'];
                    $itemPedido->percentualVerba = $item['percentualVerba'];
                    $itemPedido->base_st = $item['baseSt'];
                    $itemPedido->percentualdesconto = $item['percentualDesconto'];
                    $itemPedido->tipoacrescimodesconto = $item['tipoacrescimodesconto'];
                    $itemPedido->status = $item['status'];
                    $itemPedido->dt_cadastro = $item['dtCadastro'];
                    $itemPedido->unidvenda = $item['unidVenda'];
                    $itemPedido->custo = $item['custo'];
                    $itemPedido->margem = $item['margem'];
                    $itemPedido->pes_bru = $item['pesBru'];
                    $itemPedido->pes_liq = $item['pesLiq'];
                    $itemPedido->save();
                    if(isset($itemPedido)){
                        $update++;
                    }else{
                        $insert++;
                    }
                    
                }
                return response()->json(["message" => REGISTRO_SALVO, "resumo:" =>"Foram inseridos: {$insert} registro(s), foram atualizados: {$update} registro(s)"], 200);
            }
        } catch (Exception $ex) {
            return response()->json(["error" => true, "message" => $ex->getMessage()], 400);
        }
    }
}
