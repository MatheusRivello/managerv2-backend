<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Log;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class LogTenantController extends Controller
{
    private $service;

    public function __construct() 
    {
        $this->service = new BaseService();
    }

    public function index()
    {
        return $this->service->verificarErro(Log::paginate(20));
    }

    public function show($id)
    {
        return $this->service->verificarErro(Log::where('id', $id)->get());       
    }

    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_LOG_FILIAL);
            
            $logsTenant = new Log();
            $logsTenant->tipo = $request->tipo;
            $logsTenant->id_empresa = $this->service->usuarioLogado()->fk_empresa;
            $logsTenant->mac = $request->mac;
            $logsTenant->id_cliente = $request->id_cliente;
            $logsTenant->id_filial = $request->id_filial;
            $logsTenant->tabela = $request->tabela;
            $logsTenant->conteudo = $request->conteudo;
            $logsTenant->mensagem = $request->mensagem;
            $logsTenant->ip = $request->ip();
            $logsTenant->save();
            
            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
