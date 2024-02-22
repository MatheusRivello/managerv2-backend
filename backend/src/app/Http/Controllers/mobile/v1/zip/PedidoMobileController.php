<?php

namespace App\Http\Controllers\mobile\v1\zip;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Tenant\Pedido;
use App\Models\Tenant\PedidoItem;
use Illuminate\Support\Facades\DB;

class PedidoMobileController extends BaseMobileController
{
    protected $className;

    public function __construct()
    {
        $this->className = "Pedido";

        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    /**
     *Método principal para retornar todos os dados, seu retorno é via zip
     */
    public function completo()
    {
        $data = array(
            "pedido.json" => $this->_pedido(),
            "pedidoitem.json" => $this->_pedidoItem(),
        );

        $this->_downloadZip($this->className, $data);
    }

    /**
     * Retorna os pedidos
     * @return null|string
     */
    private function _pedido()
    {
        return $this->_verificarData($this->retornaTodosOsPedidosPorVendedorParaOAplicativo($this->usuarioLogado()->id_vendedor));
    }

    /**
     * Retorna os itens do pedido
     * @return null|string
     */
    private function _pedidoItem()
    {
        return $this->_verificarData($this->retornaTodosOsItensPorVendedorParaOAplicativo($this->usuarioLogado()->id_vendedor));
    }

    private function retornaTodosOsPedidosPorVendedorParaOAplicativo($idVendedor)
    {
        $pedido = Pedido::select(
            DB::raw("id_pedido_dispositivo AS id"),
            DB::raw("id AS id_nuvem"),
            "id_retaguarda",
            "id_endereco",
            DB::raw("'false' AS sincronizar"),
            DB::raw("'false' AS temporario"),
            DB::raw("'false' AS bloqueiaSincronizacao"),
            "dt_emissao",
            "dt_inicial",
            "id_cliente",
            "id_filial",
            "id_forma_pgto",
            "id_prazo_pgto",
            "id_tabela",
            "id_tipo_pedido",
            "id_vendedor",
            "latitude",
            "longitude",
            "margem",
            "observacao",
            "observacao_cliente",
            "enviarEmail",
            "precisao",
            "qtde_itens",
            "status",
            "status_entrega",
            "supervisor",
            "gerente",
            "pedido_original",
            "valorTotalBruto",
            "valorTotalComImpostos",
            "previsao_entrega",
            "dt_sinc_erp",
            DB::raw("dt_cadastro AS dt_sinc_nuvem"),
            "dt_entrega",
            "dt_inicial",
            "dt_emissao",
            "valorVerba",
            "bonificacaoPorVerba",
            "tipo_frete",
            "valor_acrescimo",
            "valor_desconto",
            "valor_ipi",
            "valor_st",
            "valor_total"
        )
            ->where("id_vendedor", $idVendedor)
            ->get();

        return $pedido;
    }


    public function retornaTodosOsItensPorVendedorParaOAplicativo($idVendedor)
    {
        $data = PedidoItem::select(
            DB::raw("pedido.id_pedido_dispositivo AS id_pedido"),
            "pedido.id_filial",
            "pedido_item.id_produto",
            "pedido_item.id_tabela",
            "pedido_item.base_st",
            "pedido_item.custo",
            "pedido_item.dt_cadastro",
            "pedido_item.embalagem",
            "pedido_item.margem",
            "pedido_item.numero_item",
            "pedido_item.ped_desqtd",
            "pedido_item.percentualDesconto",
            "pedido_item.percentualVerba",
            "pedido_item.quantidade",
            "pedido_item.tipoAcrescimoDesconto",
            "pedido_item.unidvenda",
            "pedido_item.valorTotalComImpostos",
            "pedido_item.valorVerba",
            "pedido_item.valor_desconto",
            "pedido_item.valor_icms",
            "pedido_item.valor_ipi",
            "pedido_item.valor_st",
            "pedido_item.valor_tabela",
            "pedido_item.valor_total",
            "pedido_item.valor_unitario"
        )
            ->join("pedido", "pedido_item.id_pedido", "=", "pedido.id")
            ->whereIn("pedido_item.id_pedido", function ($subquery) use ($idVendedor) {
                $subquery->select("id")->from("pedido")
                    ->where('id_vendedor', $idVendedor);
            })
            ->get();

        return $data;
    }
}
