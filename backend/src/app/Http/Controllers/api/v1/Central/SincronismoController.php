<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\ConfiguracaoEmpresa;
use App\Models\Central\PeriodoSincronizacao;
use App\Services\api\SincronismoService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SincronismoController extends Controller
{
    private $service;
    private $usuario;

    public function __construct()
    {
        $this->service = new SincronismoService;
        $this->usuario = $this->service->usuarioLogado();
    }

    public function indexConfigEmpresa()
    {
        $periodo = PeriodoSincronizacao::select('dia', 'hora')
            ->where('fk_empresa', $this->usuario->fk_empresa)->first();

        $config = $this->service->verificarErro(
            ConfiguracaoEmpresa::where('fk_empresa', $this->usuario->fk_empresa)
                ->with('tipoConfigSimplificada')
                ->get()
        );
        return [
            "periodo" => $periodo,
            "config" => $config
        ];
    }

    public function storeConfigEmpresa(Request $request)
    {
        try {

            ConfiguracaoEmpresa::where('fk_empresa', $this->usuario->fk_empresa)->delete();

            foreach ($request->configuracoes_empresa as $configuracao) {

                $this->service->verificarCamposConfigEmpresa($configuracao);

                $configEmpresa = new ConfiguracaoEmpresa();
                $configEmpresa->fk_empresa = $this->usuario->fk_empresa;
                $configEmpresa->fk_tipo_configuracao_empresa = $configuracao["configuracao_empresa"];
                $configEmpresa->tipo = $configuracao["tipo"];
                $configEmpresa->valor = $configuracao["valor"];
                $configEmpresa->save();
            }


            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function indexPeriodoSinc()
    {
        return $this->service->verificarErro(
            PeriodoSincronizacao::where('fk_empresa', $this->usuario->fk_empresa)
                ->first()
        );
    }

    public function updatePeriodoSinc(Request $request)
    {
        try {
            $arrayCampos = $request->all();
            $periodoSinc = PeriodoSincronizacao::firstornew(['fk_empresa'=>$this->usuario->fk_empresa]);

            if (isset($arrayCampos["dias"])) {
                $collectDias = collect($arrayCampos["dias"])->filter(function ($valor, $key) {
                    return is_bool($valor) && $valor ?? $key;
                });

                $periodoSinc->dia = $collectDias->keys()->implode(',');
            }
            
            $periodoSinc->fk_empresa = $this->usuario->fk_empresa;
            $periodoSinc->fk_usuario = $this->service->usuarioLogado()->id;
            $periodoSinc->hora = implode(',',$request->hora);
            $periodoSinc->periodo = $request->periodo;
            $periodoSinc->registro_lote = $request->registroLote;
            $periodoSinc->qtd_dias_nota_fiscal = $request->qtdDiasNotaFiscal;
            $periodoSinc->qtd_dias_nota_fiscal_app = 30;
            $periodoSinc->restricao_produto = $request->restricaoPoduto;
            $periodoSinc->restricao_protabela_preco = $request->restricaoProtabelaPreco;
            $periodoSinc->restricao_vendedor_cliente = $request->restricaoVendedorCliente;
            $periodoSinc->restricao_supervisor_cliente = $request->restricaoSupervisorCliente;
            $periodoSinc->dt_cadastro_online = $request->dtCadastroOnline;
            $periodoSinc->dt_execucao_online = $request->dtExecucaoOnline;
            $periodoSinc->dt_execucao_online_fim = $request->dtExecucaoOnlineFim;
            $periodoSinc->baixar_online = $request->baixarOnline;
            $periodoSinc->token_online_processando = $request->tokenOnlineProcessando;  
            $periodoSinc->save();
            
            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
