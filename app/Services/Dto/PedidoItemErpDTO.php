<?php

namespace App\Services\Dto;

use App\Models\Tenant\Pedido;
use App\Models\Tenant\PedidoItem;
use App\Models\Tenant\Produto;
use App\Models\Tenant\ProtabelaPreco;

class PedidoItemErpDTO {
    // campos baseado na classe PedidoDAO do serviço Windows feito em Delphi
    // campos que não são enviados para a nova API teve seu modificador alterado para private

    const PED_ITEM = 0;
    const PED_STATUS = "N";
    const PED_DESCONTO2 = 0;
    const PALM_SIG = 1;

    private $pro_cod;
    public $grupo_cod;
    public $subgrupo_cod;
    private $emp_cod;
    private $ped_item;
    public $pro_outro;
    public $ped_qtd;
    public $ped_desconto;
    public $ped_unitario;
    private $ped_status;
    private $ped_desconto2;
    public $ped_total;
    public $ped_uni;
    public $ped_embalagem;
    public $ven_cod1;
    public $tab_num;
    private $palm_sig;
    private $ped_comissao1;
    public $ped_desqtd;
    public $ped_icmsret;
    public $ped_bricms;
    public $ped_valipi;
    public $ped_valicm;
    
    public function __construct(PedidoItem $pedidoItem, Pedido $pedido, PedidoErpDTO $pedidoErpDto) {
        /**
         * @var Produto
         */
        $produto = Produto::where(['id' => $pedidoItem->id_produto])->first();
        /**
         * @var ProtabelaPreco
         */
        $tabelaPreco = ProtabelaPreco::where(['id' => $pedidoItem->id_tabela])->first();

        $this->emp_cod = $pedidoItem->id_filial;
        $this->pro_cod = $pedidoItem->id_produto;
        $this->grupo_cod = intval($produto->id_grupo);
        $this->subgrupo_cod = intval($produto->id_subgrupo);
        $this->ped_item = self::PED_ITEM;
        $this->pro_outro = str_replace(' /', '/', $produto->id_retaguarda);
        $this->ped_qtd = $pedidoItem->quantidade;
        $this->ped_desconto = $pedidoItem->percentualdesconto;
        $this->ped_unitario = $pedidoItem->valor_tabela;
        $this->ped_status = self::PED_STATUS;
        $this->ped_desconto2 = self::PED_DESCONTO2;
        $this->ped_total = $pedidoItem->valor_total;
        $this->ped_uni = str_replace(' /', '/', $pedidoItem->unidvenda);
        $this->ped_embalagem = substr($pedidoItem->embalagem, 0, 9);
        $this->ven_cod1 = $pedido->id_vendedor;
        $this->tab_num = $tabelaPreco->id_retaguarda;
        $this->palm_sig = self::PALM_SIG;
        $this->ped_comissao1 = empty($pedidoItem->ped_comissao1) ? 0 : $pedidoItem->ped_comissao1;
        $this->ped_desqtd = $pedidoItem->ped_desqtd;
        $this->ped_icmsret = $pedidoItem->valor_st;
        $this->ped_bricms = $pedidoItem->base_st;
        $this->ped_valipi = $pedidoItem->valor_ipi;
        $this->ped_valicm = empty($pedidoItem->valor_icms) ? 0 : $pedidoItem->valor_icms;
    }
}
