<?php

namespace App\Http\Controllers\Integracao;

use App\Http\Controllers\Controller;
use App\Jobs\EnviaPedidoErpJob;
use App\Models\Tenant\Pedido;
use App\Models\Tenant\PedidoItem;
use App\Services\Dto\PedidoErpDTO;
use App\Services\Dto\PedidoItemErpDTO;
use Illuminate\Http\Request;

class PedidoErpController extends Controller
{
    public function mapPedidoToErp(Request $req)
    {
        if (empty($req->idPedido)) {
            return response()->json([
                "message" => "idPedido é obrigatório!"
            ], 400);
        }
        $idPedido = $req->idPedido;

        $pedido = Pedido::where(["id" => $idPedido])->first();

        $pedidoItens = PedidoItem::where(["id_pedido" => $idPedido])->get();

        $pedidoErpDto = new PedidoErpDTO($pedido);

        foreach ($pedidoItens as $item) {
            $pedidoErpDto->itens[] = new PedidoItemErpDTO($item, $pedido, $pedidoErpDto);
        }

        return response()->json($pedidoErpDto);
    }

    public function sinc(Request $req)
    {
        $tenant = $req->tenant;
        $idPedido = $req->idPedido;
        EnviaPedidoErpJob::dispatch($tenant, $idPedido)->onQueue("pedido");

        return response()->json([
            "message" => "Pedido dispachado para fila."
        ]);
    }
}
