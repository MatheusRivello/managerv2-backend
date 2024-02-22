<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\PeriodoSincronizacao;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

class PeriodoSincronizacaoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }

    public function store(Request $request)
    {
        try {
            
            $this->service->verificarCamposRequest($request,RULE_PERIODO_SINCRONIZACAO);

            $registro = PeriodoSincronizacao::firstornew(['fk_empresa' => $request->fkEmpresa]);
            $registro->fk_empresa = $request->fkEmpresa;
            $registro->fk_usuario = $request->fkUsuario;
            $registro->dia = $request->dia;
            $registro->hora = $request->hora;
            $registro->periodo = $request->periodo;
            $registro->registro_lote = $request->registroLote;
            $registro->qtd_dias_nota_fiscal = $request->qtdDiasNotaFiscal;
            $registro->qtd_dias_nota_fiscal_app = 30;
            $registro->restricao_produto = $request->restricaoPoduto;
            $registro->restricao_protabela_preco = $request->restricaoProtabelaPreco;
            $registro->restricao_vendedor_cliente = $request->restricaoVendedorCliente;
            $registro->restricao_supervisor_cliente = $request->restricaoSupervisorCliente;
            $registro->dt_cadastro_online = $request->dtCadastroOnline;
            $registro->dt_execucao_online = $request->dtExecucaoOnline;
            $registro->dt_execucao_online_fim = $request->dtExecucaoOnlineFim;
            $registro->baixar_online = $request->baixarOnline;
            $registro->token_online_processando = $request->tokenOnlineProcessando;
            $registro->save();
            
            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
    public function show()
    {
        try {
            $registro = PeriodoSincronizacao::select(
                'fk_empresa',
                'fk_usuario',
                'qtd_dias_nota_fiscal',
                'qtd_dias_nota_fiscal_app'
            )
            ->where('fk_empresa', '=', $this->service->usuarioLogado()->fk_empresa)
            ->get();
           
                return $registro;
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
