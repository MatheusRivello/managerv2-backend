<?php

namespace App\Services\api\Tenant;

use App\Models\Tenant\TipoPedido;
use App\Services\BaseService;
use Exception;

class TipoPedidoService extends BaseService
{
    private $service;

    function __construct()
    {
        $this->service = new BaseService();
    }
    public function index()
    {
        try {
            $resultado = TipoPedido::select(
                'id',
                'id_retaguarda as idRetaguarda',
                'descricao'
            )
                ->where('status', 1)
                ->distinct()
                ->get();

            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
