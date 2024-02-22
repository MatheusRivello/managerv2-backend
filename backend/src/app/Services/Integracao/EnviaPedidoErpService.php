<?php

namespace App\Services\Integracao;

use App\Models\Tenant\Pedido;
use App\Models\Tenant\PedidoItem;
use App\Services\Dto\PedidoErpDTO;
use App\Services\Dto\PedidoItemErpDTO;
use Illuminate\Support\Facades\DB;

class EnviaPedidoErpService extends SendDataToErpService
{
    protected $idPedido;
    protected $pedido;
    protected $pedidoErpDto;
    protected $pedidoItens;
    protected $logFolder = "pedido";

    protected function getPedido()
    {
        try {
            DB::setDefaultConnection("empresa" . $this->tenant);

            $this->pedido = Pedido::where(["id" => $this->idPedido])->first();
    
            $this->pedidoItens = PedidoItem::where(["id_pedido" => $this->idPedido])->get();
        }
        catch (\Exception $e)
        {
            $this->writeLog(
                "Ocorreu um erro ao pegar o pedido. " . $e->getMessage(),
                "/pedido/erro"
            );
        }
    }

    protected function toPedidoErpDto()
    {
        $pedidoErpDto = null;
        
        try {
            $pedidoErpDto = new PedidoErpDTO($this->pedido);

            foreach ($this->pedidoItens as $item)
            {
                $pedidoErpDto->itens[] = new PedidoItemErpDTO($item, $this->pedido, $pedidoErpDto);
            }
        } 
        catch (\Exception $e)
        {
            $this->writeLog(
                "Ocorreu um erro ao mapear Pedido/PedidoItem para PedidoErpDto/PedidoItemErpDto. " . $e->getMessage(),
                "/pedido/erro"
            );
        }

        $this->data = $pedidoErpDto;
    }

    protected function savePedNumAsIdRetaguarda()
    {
        if ($this->httpCode == 200)
        {
            try {
                $data = $this->response->data;
                
                $id_retaguarda = $data->numeroPedidoInserido;

                $this->writeLog("Pedido $id_retaguarda inserido no ERP.", "/pedido"."/");
    
                $this->pedido->id_retaguarda = $id_retaguarda;

                $this->pedido->dt_sinc_erp = date('Y-m-d H:i:s');
        
                $this->pedido->save();
                DB::commit();
            }
            catch (\Exception $e) 
            {
                $this->writeLog(
                    "Ocorreu um erro ao salvar o id_retaguarda do pedido. ". $e->getMessage(),
                    "/pedido/erro"
                );
            }
        }
    }
}