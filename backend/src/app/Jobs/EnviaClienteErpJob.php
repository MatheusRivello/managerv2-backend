<?php

namespace App\Jobs;

use App\Services\Integracao\EnviaClienteErpService;
use App\Services\Integracao\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviaClienteErpJob extends EnviaClienteErpService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const PATH = "cliente/InserirClienteErp";

    public function __construct($tenant, $idCliente)
    {
        $this->tenant = $tenant;
        $this->path = self::PATH;
        $this->idCliente = $idCliente;
        $this->token = Login::getInstance()->getToken();
    }

    public function handle()
    {
        $this->getCliente();
        $this->request();
        $this->saveCliCodAsIdRetaguarda();
    }
}