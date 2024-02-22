<?php

namespace App\Http\Controllers\integracao;

use App\Services\BaseService;

class AbstractIntegracaoController
{
    protected $service;
    private $caminhoLog;
    private $caminhoLogWEB;

    //Construtor da classe
    public function __construct()
    {
        $this->service = new BaseService;
    }

    //Escreve o log da integração
    private function escreverLog(string $string, $print = true)
    {
        $string = date("d/m/Y H:i:s") . " => " . $string . "\n";

        $this->service->write_file($this->caminhoLog, $string, 'ab');

        if ($print) {
            echo $string . "<br>";
        }

        $this->service->write_file($this->caminhoLogWEB, $string, 'ab');
    }

    //Trata o catch de determinada exception
    private function trataCatch($exception, int $contador, int $time = 60): int
    {
        if ($exception->getCode() === 429) {
            $this->escreverLog("Pausado por limite de Throttling; Tentando novamente em 1min<br>");
            sleep($time);
            $contador--;
        } else {
            $this->escreverLog("Falha Produto ->{$exception->getCode()} " . $exception->getMessage() . "<br>");
        }
        return $contador;
    }

    //Pega o Log local
    private function _getLocalLog($idEmpresa, $tipo = "web", $integracao = "outros")
    {
        return  CAMINHO_PADRAO_STORAGE . "emp-{$idEmpresa}/{$integracao}/$tipo/";
    }

    //Limpar diretório
    private function limparDiretorio($idEmpresa): void
    {
        $pathCompleto = "arquivos/emp-{$idEmpresa}/shopify/completo/";
        $pathWeb = "arquivos/emp-{$idEmpresa}/shopify/web/";
        $log = glob("{$pathCompleto}" . date("Y_m") . "*.txt");
        $diretorioWeb = dir($pathWeb);

        while ($arquivoWeb = $diretorioWeb->read()) {
            if (
                $arquivoWeb <> (date("Y_m_d_H") . ".txt")
                && $arquivoWeb <> '.'
                && $arquivoWeb <> '..'
            ) {
                unlink($pathWeb . $arquivoWeb);
            }
        }

        $diretorioWeb->close();

        $diretorioCompleto = dir($pathCompleto);

        while ($arquivoCompleto = $diretorioCompleto->read()) {
            if (
                $arquivoCompleto <> (date("Y_m_d_H") . ".txt")
                && $arquivoCompleto <> '.'
                && $arquivoCompleto <> '..'
                && preg_match("/" . date("Y_m") . "/", $arquivoCompleto) != 1
            ) {
                unlink($pathCompleto . $arquivoCompleto);
            }
        }

        $diretorioCompleto->close();
    }
    //Define o caminho do log
    protected function defineCaminhoLog($idEmpresa, $nomeDaIntegracao): void
    {
        $logCompleto = CAMINHO_PADRAO_STORAGE . "emp-{$idEmpresa}/{$nomeDaIntegracao}/completo/";
        $logWEB = CAMINHO_PADRAO_STORAGE . "emp-{$idEmpresa}/{$nomeDaIntegracao}/web/";

        if (!is_dir($logCompleto) || !is_dir($logWEB)) {
            mkdir($logCompleto, 0755, true);
            mkdir($logWEB, 0755, true);
        }

        $this->caminhoLog = $logCompleto . date("Y_m_d") . ".txt";
        $this->caminhoLogWEB = $logWEB . date("Y_m_d_H") . ".txt";
        $this->limparDiretorio($idEmpresa);
    }
}
