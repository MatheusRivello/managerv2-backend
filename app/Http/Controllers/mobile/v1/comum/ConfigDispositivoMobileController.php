<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use Illuminate\Http\Request;
use App\Models\Central\ConfiguracaoDispositivo;

class ConfigDispositivoMobileController extends BaseMobileController
{
    protected $className;

    public function __construct(Request $request)
    {
        $this->className = "Configuracaodispositivo";
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    public function configuracao()
    {
        $data = ConfiguracaoDispositivo::select(
            "configuracao_dispositivo.id",
            "tipo_configuracao.descricao",
            "tipo_configuracao.tipo",
            "configuracao_dispositivo.valor"
        )
            ->where("fk_dispositivo", $this->usuarioLogado()->id)
            ->join("tipo_configuracao", "configuracao_dispositivo.fk_tipo_configuracao", "=", "tipo_configuracao.id")
            ->get();

        $resposta = is_null($data) ? [
            'status' => 'erro',
            'code' => HTTP_NOT_FOUND,
            'mensagem' => 'Nenhum registro localizado com o MAC ' . $this->mac
        ] : [
            'status' => 'sucesso',
            'code' => HTTP_ACCEPTED,
            'data' => $data
        ];

        return response()->json($resposta, $resposta['code']);
    }
}
