<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\ServicoLocal;
use App\Services\BaseService;

class StatusServicoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
        $this->usuario = $this->service->usuarioLogado();
    }

    public function index()
    {
        if (is_null($this->usuario->fk_empresa)) {
            $status = collect(ServicoLocal::all())->map(function ($item) {
                $infoStatus = $this->service->verificaServicoLocal($item);
                return is_null($infoStatus) ?: $infoStatus;
            });
        } else {
            $status = $this->service->verificaServicoLocal(ServicoLocal::where('fk_empresa', $this->usuario->fk_empresa)->first());
        }

        return $this->service->verificarErro($status);
    }
}
