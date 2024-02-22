<?php

namespace App\Http\Controllers\mobile\v1\zip;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Central\HorarioUtilizacaoDispositivo;

class EssencialMobileController extends BaseMobileController
{
    protected $className;

    public function __construct()
    {
        $this->className = "Essencial";

        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    /**
     *Método principal para retornar todos os dados, seu retorno é via zip
     */
    public function essencial()
    {
        $data = array(
            "filial.json" => $this->_filial(),
            "statuscliente.json" => $this->_statusCliente(),
            "tipopedido.json" => $this->_tipoPedido(),
            "indicadormargem.json" => $this->_indicadorMargem(),
            "atividade.json" => $this->_atividade(),
            "motivo.json" => $this->_motivo(),
            "aviso.json" => $this->_aviso(),
            "formapagamento.json" => $this->_forma(),
            "prazopagamento.json" => $this->_prazo(),
            "vendedorprazo.json" => $this->_vendedorPrazo(),
            "formaprazo.json" => $this->_fp(),
            "configfilial.json" => $this->_configuracaoFilial(),
            "horarioacesso.json" => $this->_horarioAcesso(),
            "statuspedido.json" => $this->_statusPedido()
        );

        $this->_downloadZip($this->className, $data);
    }

    /**
     * Retorna status do pedido
     * @return null|string
     */
    private function _statusPedido()
    {
        $dataRetroativa = $this->retrocederOuAvancarData(date("Y-m-d"), "Y-m-d", 3, "-", "days");
        $data = $this->_getAllPorTabela(TABELA_STATUS_PEDIDO, NULL, "data >= '{$dataRetroativa}'");
        return $this->_verificarData((!is_null($data)) ? $data : NULL);
    }

    /**
     * Retorna os horários para acesso
     * @return null|string
     */
    private function _horarioAcesso()
    {
        $data = $this->retornaHorario($this->usuarioLogado()->fk_empresa, $this->usuarioLogado()->id_vendedor, $this->usuarioLogado()->id);
        return $this->_verificarData((!is_null($data)) ? [$data] : NULL);
    }

    /**
     * Retorna o vendedor com seu prazo de pagamento
     * @return null|string
     */
    private function _vendedorPrazo()
    {
        //TODO REMOVER IF DEPOIS DE ATUALIZAR TODAS AS EMPRESAS
        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_VENDEDOR_PRAZO)) {
            return $this->_verificarData($this->_getAllPorTabela(TABELA_VENDEDOR_PRAZO, NULL, "id_vendedor = {$this->usuarioLogado()->id_vendedor}"));
        } else {
            return $this->_verificarData(NULL);
        }
    }

    /**
     * Retorna a filial
     * @return null|string
     */
    private function _filial()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_FILIAL));
    }

    /**
     * Retorna os status do cliente
     * @return null|string
     */
    private function _statusCliente()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_STATUS_CLIENTE));
    }

    /**
     * Retorna o indicador de margem
     * @return null|string
     */
    private function _indicadorMargem()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_INDICADOR_MARGEM));
    }

    /**
     * Retorna o tipo de pedido
     * @return null|string
     */
    private function _tipoPedido()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_TIPO_PEDIDO));
    }

    /**
     * Retorna as atividades
     * @return null|string
     */
    private function _atividade()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_ATIVIDADE));
    }

    /**
     * Retorna os motivos
     * @return null|string
     */
    private function _motivo()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_MOTIVO));
    }

    /**
     * Retorna os avisos
     * @return null|string
     */
    private function _aviso()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_AVISO));
    }

    /**
     * Retorna as formas de pagamento
     * @return null|string
     */
    private function _forma()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_FORMA_PAGAMENTO));
    }

    /**
     * Retorna os prazos de pagamento
     * @return null|string
     */
    private function _prazo()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_PRAZO_PAGAMENTO));
    }

    /**
     * Retorna forma e prazos de pagamento
     * @return null|string
     */
    private function _fp()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_FORMA_PRAZO_PGTO));
    }

    /**
     * Retorna configuração da filial
     * @return null|string
     */
    private function _configuracaoFilial()
    {
        return $this->_verificarData($this->_getAllPorTabela(TABELA_CONFIGURACAO_FILIAL));
    }

    public function retornaHorario($idEmpresa = FALSE, $idVendedor = FALSE, $idDispositivo = FALSE)
    {
        $data = HorarioUtilizacaoDispositivo::select("horario_utilizacao_dispositivo.*");

        if ($idEmpresa != FALSE) {
            $data->where("horario_utilizacao_dispositivo.fk_empresa", $idEmpresa);
        }
        if ($idVendedor != FALSE) {
            $data->where("horario_utilizacao_dispositivo.id_vendedor", $idVendedor);
        }
        if ($idDispositivo != FALSE) {
            $data->where("horario_utilizacao_dispositivo.fk_dispositivo", $idDispositivo);
        }

        $data->join("horario", "horario.id", "=", "horario_utilizacao_dispositivo.fk_horario");

        return $data->get();
    }
}
