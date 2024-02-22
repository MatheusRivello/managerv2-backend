<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Services\api\Tenant\TabelaPrecoService;
/**
 * @OA\Get(
 *     path="/api/tenant/tabela-preco",
 *     summary="Lista as tabelas de preco",
 *     description="Lista as tabelas de preco.",
 *     operationId="Lista as tabelas de preco",
 *     tags={"Tabela de Preço"},
 *     @OA\Response(
 *         response=200,
 *         description="Devolve um array de tabela de preços.",
 *         @OA\JsonContent(type="array",@OA\Items(ref="App\Models\Tenant\ProtabelaPreco"))    
 *      ), 
 *     @OA\Response(
 *       response=403,
 *       description="O token está expirado"
 *     )
 *      
 * )
 **/
class TabelaPrecoController extends Controller
{

    private $tabelaPreco;

    public function __construct(TabelaPrecoService $tabelaPreco)
    {
        $this->tabelaPreco = $tabelaPreco;
    }

    public function getAll()
    {
        return $this->tabelaPreco->index();
    }
}
