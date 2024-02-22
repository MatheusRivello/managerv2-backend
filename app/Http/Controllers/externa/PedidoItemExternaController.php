<?php

use App\Http\Controllers\externa\AbstractExternaController;

namespace App\Http\Controllers\externa;

use App\Http\Controllers\externa\AbstractExternaController;
use App\Models\Tenant\PedidoItem;
use App\Services\BaseService;
use Illuminate\Http\Request;

class PedidoItemExternaController extends AbstractExternaController
{
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->Entity = PedidoItem::class;
        $this->filters = ['id_pedido', 'id_produto ', 'numero_item', 'id_tabela', 'status', 'dt_cadastro'];
        $this->relationCountMethodName = 'getRelacionamentosCount';
        $this->rulePath = RULE_PEDIDO_ITEM_EXTERNA;
        $this->firstOrNew = ['id_pedido', 'id_produto', 'numero_item', 'id_tabela', 'status', 'dt_cadastro'];
        $this->modelComBarra = "\PedidoItem";
        $this->tabela = 'pedido_item';
        $this->fields = [
            'id_pedido',
            'numero_item',
            'id_produto',
            'id_tabela',
            'embalagem',
            'quantidade',
            'valor_total',
            'valor_st',
            'valor_ipi',
            'valor_tabela',
            'valor_unitario',
            'valor_desconto',
            'cashback',
            'unitario_cashback',
            'valor_frete',
            'valor_seguro',
            'valorVerba',
            'valorTotalComImpostos',
            'valor_icms',
            'ped_desqtd',
            'percentualVerba',
            'base_st',
            'percentualdesconto',
            'tipoacrescimodesconto',
            'status',
            'dt_cadastro',
            'unidvenda',
            'custo',
            'margem',
            'pes_bru',
            'pes_liq'
         ];
    }

    public function destroyPedidoItem(?Request $request, $where = null)
    {
        if (isset($request)) {
            $where = ['id_pedido' => $request->idPedido, 'numero_item' => $request->numeroItem];
        }
        return $this->destroyWhere($where);
    }

    public function storePedidoItem(Request $request)
    {
        $where = ['id_pedido' => $request->idPedido, 'numero_item' => $request->numeroItem];
        $this->destroyPedidoItem(null, $where);
        return $this->storePersonalizado($request);
    }
}
