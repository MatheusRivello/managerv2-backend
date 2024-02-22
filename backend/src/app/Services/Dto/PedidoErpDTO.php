<?php

namespace App\Services\Dto;

use App\Models\Tenant\Cliente;
use App\Models\Tenant\Endereco;
use App\Models\Tenant\FormaPagamento;
use App\Models\Tenant\Pedido;
use App\Models\Tenant\ProtabelaPreco;
use App\Models\Tenant\VendaPlano;

class PedidoErpDTO {
    // campos baseado na classe PedidoDAO do serviço Windows feito em Delphi
    // campos que não são enviados para a nova API teve seu modificador alterado para private

    const TIT_COD_ENT = 1;
    const TRANSP_COD = 1;
    const PED_ORC = "N";
    const PED_STATUS = "N";
    const PED_OK = -1;
    const CONTA_COD = 0;
    const PALM_SIG = 1;
    const PED_PAGO_AFV = 0;

    public $emp_cod;
    public $id_pedido_dispositivo;
    private $ped_palmtop;
    private $ped_palmtop_2;
    public $cli_cod;
    public $tit_cod_ent;
    public $transp_cod;
    public $mac;
    public $modo_cobr;
    public $prazo_pag;
    public $ped_emissao;
    private $ped_hor_ini;
    public $ped_descfin;
    public $ped_obs_int;
    public $ped_orc;
    public $ped_org;
    public $ped_status;
    private $ped_ok;
    public $ped_ok_data;
    public $dat_cad;
    public $dt_inicial;
    public $conta_cod;
    public $ped_subtotal;
    public $ped_total;
    public $ped_itens;
    public $ven_cod1;
    public $tab_num;
    public $ped_tipo;
    private $palm_sig;
    public $ped_obs;
    public $ped_entrega;
    private $ped_hor_fim;
    public $obra_cod;
    public $ped_frete;
    public $ped_seguro;
    public $ped_icmsret;
    public $ped_bricms;
    public $ped_valipi;
    public $ped_valicm;
    public $nfs_num;
    public $ped_pago_afv;
    public $ped_bon_afv_verba;
    public $ped_tpfrete;
    public $ped_ped_cli;
    public $itens;
    
    public function __construct(Pedido $pedido) {
        $pedPalmtop2 = $pedido->origem . date('dmy', time()) . $pedido->id_vendedor . 
            $pedido->id_pedido_dispositivo . str_replace(':', '', substr($pedido->mac, 5, 4));

        if ($pedido->origem === 'P') {
            $pedPalmtop = 'AFV' . $pedido->id_vendedor . $pedido->id_pedido_dispositivo;
        } else {
            $pedPalmtop = 'W' . $pedido->id_vendedor . $pedido->id_pedido_dispositivo;
        }
        /**
         * @var FormaPagamento
         */
        $formaPgto = FormaPagamento::where(['id' => $pedido->id_forma_pgto])->first();
        /**
         * @var ProtabelaPreco
         */
        $tabelaPreco = ProtabelaPreco::where(['id' => $pedido->id_tabela])->first();
        /**
         * @var Cliente
         */
        $cliente = Cliente::where(['id' => $pedido->id_cliente])->first();

        $idRetaguardaEndereco = implode(".", array($pedido->id_filial, $cliente->id_retaguarda, self::TIT_COD_ENT));
        /**
         * @var Endereco
         */
        $endereco = Endereco::where(['id_retaguarda' => $idRetaguardaEndereco])->first();

        $this->emp_cod = $pedido->id_filial;
        $this->id_pedido_dispositivo = strval($pedido->id_pedido_dispositivo);
        $this->ped_palmtop = substr($pedPalmtop, 0, 9);
        $this->ped_palmtop_2 = $pedPalmtop2;
        $this->cli_cod = $cliente->id_retaguarda;
        $this->tit_cod_ent = self::TIT_COD_ENT;
        $this->transp_cod = self::TRANSP_COD;
        $this->mac = $pedido->mac;
        $this->modo_cobr = $formaPgto->id_retaguarda;
        $this->prazo_pag = str_replace([' / /', ' /'], '/', $formaPgto->id_retaguarda);
        $this->ped_emissao = $pedido->dt_emissao;
        $this->ped_hor_ini = substr($pedido->dt_inicial, 11, 5);
        $this->ped_descfin = $pedido->valor_desconto;
        $this->ped_obs_int = $pedido->observacao;
        $this->ped_orc = self::PED_ORC;
        $this->ped_org = $pedido->origem;
        $this->ped_status = self::PED_STATUS;
        $this->ped_ok = self::PED_OK;
        $this->ped_ok_data = $pedido->dt_emissao;
        $this->dat_cad = date(now());
        $this->dt_inicial = empty($pedido->dt_inicial) ? null : $pedido->dt_inicial;
        $this->conta_cod = self::CONTA_COD;
        $this->ped_subtotal = $pedido->valor_total;
        $this->ped_total = $pedido->valorTotalComImpostos;
        $this->ped_itens = $pedido->qtde_itens;
        $this->ven_cod1 = $pedido->id_vendedor;
        $this->tab_num = $tabelaPreco->id_retaguarda;
        $this->ped_tipo = $pedido->tipo_pedido->id_retaguarda;
        $this->palm_sig = self::PALM_SIG;
        $this->ped_obs = is_null($pedido->observacao_cliente) ? "" : $pedido->observacao_cliente;
        $this->ped_entrega = empty($pedido->previsao_entrega) ? null : $pedido->previsao_entrega;
        $this->ped_hor_fim = substr($pedido->dt_emissao, 11, 5);
        $this->obra_cod = empty($endereco->tit_cod) ? 0 : intVal($endereco->tit_cod);
        $this->ped_frete = empty($pedido->valor_frete) ? 0 : $pedido->valor_frete;
        $this->ped_seguro = empty($pedido->valor_seguro) ? 0 : $pedido->valor_seguro;
        $this->ped_icmsret = $pedido->valor_st;
        $this->ped_bricms = empty($pedido->valor_st) ? 0 : $pedido->valor_st;
        $this->ped_valipi = empty($pedido->valor_ipi) ? 0 : $pedido->valor_ipi;
        $this->ped_valicm = empty($pedido->valor_icms) ? 0 : $pedido->valor_icms;
        $this->nfs_num = empty($pedido->nfs_num) ? 0 : $pedido->nfs_num;
        $this->ped_pago_afv = self::PED_PAGO_AFV;
        $this->ped_bon_afv_verba = empty($pedido->bonificacaoPorVerba) ? 0 : $pedido->bonificacaoPorVerba;
        $this->ped_tpfrete = empty($pedido->tipo_frete) ? 0 : $pedido->tipo_frete;
        $this->ped_ped_cli = $cliente->id_retaguarda;
    }
}
