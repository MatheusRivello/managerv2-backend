<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use Illuminate\Http\Request;
use App\Models\Central\Dispositivo;
use App\Models\Central\Empresa;
use App\Models\Central\VersaoApp;
use Illuminate\Support\Facades\DB;

class DispositivoMobileController extends BaseMobileController
{
    protected $className;

    public function __construct(Request $request)
    {
        $this->className = "dispositivo";
        $this->model = Dispositivo::class;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    /**
     *METODO PARA VERIFICAR SE O DISPOSITIVO ESTA AUTENTICADO
     */
    public function verificar(Request $request)
    {
        $empresa = Empresa::whereId($this->usuarioLogado()->fk_empresa)
            ->first();

        if ($empresa->status == 1) {
            $licenca = $this->model::select('licenca')
                ->whereMac($this->mac)
                ->first()
                ->licenca;

            $versaoAtual = VersaoApp::select(
                DB::raw("TRIM(versao) AS versao"),
                "obrigatorio"
            )
                ->orderBy('codigo_versao', 'DESC')
                ->first();

            $data["licenca"] = LICENCA_NAO_EXISTE;

            if (!is_null($licenca)) {
                $data["licenca"] = intval($licenca);

                if ($versaoAtual["obrigatorio"] == OPCAO_SIM && $versaoAtual["versao"] !== $this->versaoApp) {
                    $data["licenca"] = LICENCA_BLOQUEADA_ATUALIZACAO;
                }

                $vetor = [
                    "mac" => $this->mac,
                    "token_push" => $request->token,
                    "versaoApp" => $this->versaoApp,
                    "id_unico" => $request->idUnico,
                ];

                if (isset($request->marca)) $vetor["marca"] = $request->marca;
                if (isset($request->versaoAndroid)) $vetor["versao_android"] = $request->versaoAndroid;
                if (isset($request->modelo)) $vetor["modelo"] = $request->modelo;

                $this->atualizarDispositivo($vetor, $this->mac);
            }
        } else {
            // SE A EMPRESA NÃO ESTIVER ATIVA ELE RETORNA 3 COMO BLOQUEADA
            $data["licenca"] = LICENCA_BLOQUEADA; // Empresa bloqueada
        }

        $data["empresa"] = $empresa->id;

        $resposta = [
            'code' => HTTP_OK,
            'status' => 'sucesso',
            'data' => $data
        ];

        return response()->json($resposta, HTTP_CREATED);
    }

    private function atualizarDispositivo($vetor, $mac)
    {
        $dispositivo = $this->model::firstOrNew(["mac" => $mac]);

        foreach ($vetor as $coluna => $valor) {
            $dispositivo->{$coluna} = $valor;
        }

        $dispositivo->save();
    }
}
