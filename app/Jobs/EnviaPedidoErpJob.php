<?php

namespace App\Jobs;

use App\Services\Integracao\EnviaPedidoErpService;
use App\Services\Integracao\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviaPedidoErpJob extends EnviaPedidoErpService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const PATH = "pedido/InserirPedidoErp";

    public function __construct($tenant, $idPedido)
    {
        $this->tenant = $tenant;
        $this->path = self::PATH;
        $this->idPedido = $idPedido;
        $this->token = Login::getInstance()->getToken();
    }

    public function handle()
    {
        $this->getPedido();
        $this->toPedidoErpDto();
        $this->request();
        $this->savePedNumAsIdRetaguarda();
    }
}
