<?php

namespace App\Services\api;

use App\Models\Central\CercaEletronica;
use App\Models\Central\Dispositivo;
use App\Services\BaseService;
use Exception;


class CercaEletronicaService extends BaseService
{
    private $DATA;
    private $DIA;
    private $MES;
    private $HORA;
    private $MINUTO;

    private $AA;
    private $BB;
    private $CC;
    private $DD;

    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
        
        $this->DATA = explode("-", date("d-m-H-i-s"));
        $this->DIA = $this->DATA[0];
        $this->MES = $this->DATA[1];
        $this->HORA = $this->DATA[2];
        $this->MINUTO = $this->DATA[3];

        $this->AA = $this->atribuirDigitoZeroNoInicio(rand(00, 19));
        $this->BB = $this->atribuirDigitoZeroNoInicio(rand(20, 39));
        $this->CC = $this->atribuirDigitoZeroNoInicio(rand(40, 59));
        $this->DD = $this->atribuirDigitoZeroNoInicio(rand(60, 79));
    }


    public function atribuirDigitoZeroNoInicio($digito)
    {
        $digito = strval($digito);
        if (
            $digito === "0" ||
            $digito === "1" ||
            $digito === "2" ||
            $digito === "3" ||
            $digito === "4" ||
            $digito === "5" ||
            $digito === "6" ||
            $digito === "7" ||
            $digito === "8" ||
            $digito === "9"
        ) {
            $digito = "0" . $digito;
        }
        return $digito;
    }

    public function gerarToken()
    {
        $tipoAIgnorar = $this->randomTipo();

        $array = [];
        if ($tipoAIgnorar != "AA") array_push($array, $this->AA);
        if ($tipoAIgnorar != "BB") array_push($array, $this->BB);
        if ($tipoAIgnorar != "CC") array_push($array, $this->CC);
        if ($tipoAIgnorar != "DD") array_push($array, $this->DD);

        //Embaralha o array de numeros
        $rand_keys = array_rand($array, 1);
        $sort = $array[$rand_keys]; //Pega o numero sorteado atraves da posição informada pelo embaralhador

        $tipoGerado = "";
        $token = "";

        if ($sort >= 0 && $sort <= 19 && $tipoAIgnorar !== "AA") {//Tipo AA
            $tipoGerado = "AA";
            $token = $this->AA . $this->MINUTO . $this->DIA . $this->MES . $this->HORA;
        }
        elseif ($sort >= 20 && $sort <= 39 && $tipoAIgnorar !== "BB") {//Tipo BB
            $tipoGerado = "BB";
            $token = $this->BB . $this->MES . $this->MINUTO . $this->DIA . $this->HORA;
        }
        elseif ($sort >= 40 && $sort <= 59 && $tipoAIgnorar !== "CC") {//Tipo CC
            $tipoGerado = "CC";
            $token = $this->CC . $this->MES . $this->HORA . $this->DIA . $this->MINUTO;
        }
        elseif ($sort >= 60 && $sort <= 79 && $tipoAIgnorar !== "DD") {//Tipo DD
            $tipoGerado = "DD";
            $token = $this->DD . $this->HORA . $this->MES . $this->MINUTO . $this->DIA;
        }

        return [
            "tipoGerado" => $tipoGerado,
            "token" => $token,
        ];
    }

    private function randomTipo()
    {
        $array = array('AA', 'BB', 'CC', 'DD');
        return $this->array_random($array);
    }

    public function validaTokenDispositivo($idVendedor, $idEmpresa, $status = 1)
    {
        $dispositivo = Dispositivo::where([['id_vendedor', $idVendedor], ['status', $status], ['fk_empresa', $idEmpresa]])
            ->get(['token_push', 'mac'])->first();

        if ($dispositivo) {
            return [
                "token_push" => $dispositivo->token_push,
                "mac" => $dispositivo->mac,
            ];
        } else {
            throw new Exception(TOKEN_INVALIDO, 409);
        }
    }
}
