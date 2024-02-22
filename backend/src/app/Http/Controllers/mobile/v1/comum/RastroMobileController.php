<?php

namespace App\Http\Controllers\mobile\v1\comum;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Tenant\Rastro;
use App\Models\Tenant\Vendedor;
use Illuminate\Http\Request;

class RastroMobileController extends BaseMobileController
{
    protected $className;

    public function __construct(Request $request)
    {
        $this->className = "Rastro";
        $this->model = Rastro::class;
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    /**
     *METODO PARA INSERIR O RASTRO DO VENDEDOR
     */
    public function rastrojson(Request $request)
    {
        $array = $request->json;
        if (is_array($array)) {
            $data = $this->rastro($array, $this->mac, $array[0]["id_vendedor"]);

            $resposta = is_array($data[0]) ? [
                'code' => HTTP_OK,
                'status' => 'sucesso',
                'data' => $data
            ] : [
                'code' => HTTP_NOT_FOUND,
                'status' => 'erro',
                'mensagem' => "erro ao inserir",
                'data' => $data
            ];
        } else {
            $resposta = [
                'code' => HTTP_NOT_FOUND,
                'status' => 'erro',
                'mensagem' => "Json esta vazio ou é inválido",
                'data' => $array
            ];
        }

        return response()->json($resposta, HTTP_CREATED);
    }

    private function rastro($dados, $mac, $id_vendedor)
    {
        $retorno = [];

        foreach ($dados as &$linha) {
            $vendedor = Vendedor::find($id_vendedor);

            if (!isset($vendedor)) {
                $retorno[] = "Vendedor=" . $id_vendedor . " Nao existe";
            } else {
                // $linha["hora"] = str_replace('HH', '00', $linha["hora"]);

                $linha["id_vendedor"] = $id_vendedor;
                $linha["mac"] = $mac;
                $linha["sinc_erp"] = SYNC_NAO_BAIXAR_SIG; //(1)->Baixar para o sig / (0) -> Não baixar

                $retorno[]["inserido"] = $this->inserirRegistro($linha) ? REGISTRO_INSERIDO : NAO_REGISTRADO; // 1= INSERIDO, 0=HOUVE ALGUM ERRO
            }
        }

        return $retorno;
    }

    private function inserirRegistro($dado)
    {
        $json = json_decode(json_encode($dado));

        $rastro = new $this->model;
        $rastro->id_vendedor = $json->id_vendedor;
        $rastro->data = $json->data;
        $rastro->hora = $json->hora;
        $rastro->latitude = $json->latitude;
        $rastro->longitude = $json->longitude;
        $rastro->velocidade = $json->velocidade;
        $rastro->altitude = $json->altitude;
        $rastro->direcao = $json->direcao;
        $rastro->provedor = isset($json->provedor) ? $json->provedor : PROVEDOR_PADRAO_RASTRO;
        $rastro->precisao = $json->precisao;
        $rastro->mac = $json->mac;
        $rastro->sinc_erp = $json->sinc_erp;

        if ($rastro->save()) {
            $retorno = true;
        } else {
            $retorno = false;
        }

        return $retorno;
    }
}
