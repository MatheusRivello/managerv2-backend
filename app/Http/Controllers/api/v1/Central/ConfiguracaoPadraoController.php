<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\ConfiguracaoDispositivo;
use App\Models\Central\ConfiguracaoDispositivoPadrao;
use App\Models\Central\Horario;
use App\Models\Central\HorarioUtilizacaoDispositivoPadrao;
use App\Services\api\ConfigPadraoService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracaoPadraoController extends Controller
{
    private $service;
    private $usuario;

    public function __construct()
    {
        $this->service = new ConfigPadraoService;
        $this->usuario = $this->service->usuarioLogado();
    }

    public function index()
    {
        $configDefault = ConfiguracaoDispositivoPadrao::where('fk_empresa', $this->usuario->fk_empresa)
            ->with('tipo_configuracao')
            ->get();

        $horarioDefault = HorarioUtilizacaoDispositivoPadrao::where('fk_empresa', $this->usuario->fk_empresa)
            ->with('horario')
            ->first();

        $response = array(
            "configuracao_padrao" => sizeof($configDefault) > 0 ? $configDefault : CONFIGURACAO_NAO_ENCONTRADA,
            "horario_padrao" => isset($horarioDefault) ? $horarioDefault : HORARIO_NAO_ENCONTRADA
        );

        return $this->service->verificarErro($response);
    }

    public function show($id)
    {
        return  $this->service->verificarErro(ConfiguracaoDispositivoPadrao::with('label_tipo_config')
            ->where(
                [
                    ['fk_empresa', $this->usuario->fk_empresa],
                    ['id', $id]
                ]
            )
            ->get());
    }

    public function store(Request $request)
    {
        try {

            ConfiguracaoDispositivoPadrao::where(
                'fk_empresa',
                $this->usuario->fk_empresa
            )->delete();

            foreach ($request->configuracao_dispositivos as $configuracao) {
                $this->service->verificarCamposRequest($configuracao, RULE_CONFIG_PADRAO_CENTRAL);
                $configDefault = new ConfiguracaoDispositivoPadrao();
                $configDefault->fk_empresa = $this->usuario->fk_empresa;
                $configDefault->fk_tipo_configuracao = $configuracao["tipo_configuracao"];
                $configDefault->valor = $configuracao["valor"];
                $configDefault->save();
            }

            $horarioBase = new Horario();
            $horarioBase->seg_i = $request->horarios["seg_i"];
            $horarioBase->seg_f = $request->horarios["seg_f"];
            $horarioBase->ter_i = $request->horarios["ter_i"];
            $horarioBase->ter_f = $request->horarios["ter_f"];
            $horarioBase->qua_i = $request->horarios["qua_i"];
            $horarioBase->qua_f = $request->horarios["qua_f"];
            $horarioBase->qui_i = $request->horarios["qui_i"];
            $horarioBase->qui_f = $request->horarios["qui_f"];
            $horarioBase->sex_i = $request->horarios["sex_i"];
            $horarioBase->sex_f = $request->horarios["sex_f"];
            $horarioBase->sab_i = $request->horarios["sab_i"];
            $horarioBase->sab_f = $request->horarios["sab_f"];
            $horarioBase->dom_i = $request->horarios["dom_i"];
            $horarioBase->dom_f = $request->horarios["dom_f"];

            $horarioBase->status_seg = $request->horarios["status_seg"];
            $horarioBase->status_ter = $request->horarios["status_ter"];
            $horarioBase->status_qua = $request->horarios["status_qua"];
            $horarioBase->status_qui = $request->horarios["status_qui"];
            $horarioBase->status_sex = $request->horarios["status_sex"];
            $horarioBase->status_sab = $request->horarios["status_sab"];
            $horarioBase->status_dom = $request->horarios["status_dom"];

            if ($horarioBase->save()) {
                $this->ajusteHorario();

                $horarioDispositivo = new HorarioUtilizacaoDispositivoPadrao();
                $horarioDispositivo->fk_empresa = $this->usuario->fk_empresa;
                $horarioDispositivo->fk_horario = $horarioBase->id;
                $horarioDispositivo->status_padrao = $request->utiliza_horario;
                $horarioDispositivo->save();
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function applyToAll(Request $request)
    {
        try {
            $this->applyToAllDevices();
            return response()->json([
                'message' => 'Configuração aplicada em todos os dispositivos.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ouve um erro ao aplicar a configuração em todos os dispositivos.' . $th->getMessage()
            ], 400);
        }
    }

    private function applyToAllDevices()
    {
        /**
         * @var Collection<ConfiguracaoDispositivoPadrao>
         */
        $configsPadrao = ConfiguracaoDispositivoPadrao::where([
                'fk_empresa' => $this->usuario->fk_empresa,
            ])->get();

        /**
         * @var ConfiguracaoDispositivoPadrao $config
         */
        foreach ($configsPadrao->toArray() as $config) {
            ConfiguracaoDispositivo::where([
                'fk_empresa' => $this->usuario->fk_empresa,
                'fk_tipo_configuracao' => $config['fk_tipo_configuracao']
            ])->update([
                'valor' => $config['valor']
            ]);
            DB::commit();
        }
    }

    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $configDefault = ConfiguracaoDispositivoPadrao::find($id);

            if (!isset($configDefault)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($configDefault->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function ajusteHorario()
    {
        $horaDefault = HorarioUtilizacaoDispositivoPadrao::where(
            'fk_empresa',
            $this->usuario->fk_empresa
        )->first();
        if ($horaDefault) {
            $horaDefault->delete();
            Horario::where('id', $horaDefault->fk_horario)->delete();
        }
    }
}
