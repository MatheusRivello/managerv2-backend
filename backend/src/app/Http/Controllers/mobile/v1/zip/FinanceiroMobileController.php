<?php

namespace App\Http\Controllers\mobile\v1\zip;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use App\Models\Tenant\Meta;
use App\Models\Tenant\MetaDetalhe;
use App\Models\Tenant\NotaFiscalItem;
use App\Models\Tenant\TituloFinanceiro;
use DateTime;

class FinanceiroMobileController extends BaseMobileController
{
    protected $className;

    public function __construct()
    {
        $this->className = "InfoFinanceira";

        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    /**
     *Método principal para retornar todos os dados, seu retorno é via zip
     */
    public function infofinanceira()
    {
        $data = array(
            "notafiscal.json" => $this->_notaFiscal(),
            "notafiscalitem.json" => $this->_notaFiscalItem(),
            "titulo.json" => $this->_tituloFinanceiro(),
            "meta.json" => $this->_meta(),
            "metadetalhe.json" => $this->_metaDetalhe()
        );

        $this->_downloadZip($this->className, $data);
    }

    /**
     * Retorna a nota fiscal
     * @return null|string
     * @throws Exception
     */
    private function _notaFiscal()
    {
        $where = NULL;
        if ($this->restricaoParaConsultaNoBanco == "dadosDeTodosOsVendedoresPorSupervisor") {
            $data = $this->defineFiltroSupervisorNota();
            if (!is_null($data)) {
                $where["nota_fiscal.ped_emissao>="] = $data;
            }
        }
        return $this->_verificarData($this->_retornoDadosPorVendedorOuSupervisor(TABELA_NOTA_FISCAL, $where));
    }

    /**
     * Retorna os itens da nota fiscal
     * @return null|string
     * @throws Exception
     */
    private
    function _notaFiscalItem()
    {
        //Caso for restrito para vendedor_cliente
        if ($this->restricaoParaConsultaNoBanco == "somenteDadosDoVendedor") {
            return $this->_verificarData($this->getPorVendedor($this->usuarioLogado()->id_vendedor));
        } else if ($this->restricaoParaConsultaNoBanco == "dadosDeTodosOsVendedoresPorSupervisor") { //Caso for restrito por vendedores do supervisor
            return $this->_verificarData($this->getPorVendedor($this->vendedoresPorSupervisorOuGerente(), $this->defineFiltroSupervisorNota()));
        } else { //Caso não haja nenhuma restrição
            return $this->_verificarData($this->getPorVendedor());
        }
    }

    /**
     * Retorna os titulos financeiros
     * @return null|string
     */
    private
    function _tituloFinanceiro()
    {
        $idVendedores = [];
        $restricaoVendedorCliente = false;
        if ($this->restricaoParaConsultaNoBanco == "somenteDadosDoVendedor") {
            $restricaoVendedorCliente = true;
            $idVendedores = [$this->usuarioLogado()->id_vendedor];
        } else if ($this->restricaoParaConsultaNoBanco == "dadosDeTodosOsVendedoresPorSupervisor") {
            $restricaoVendedorCliente = true;
            $idVendedores = $this->vendedoresPorSupervisorOuGerente();
        }

        return $this->_verificarData(
            $this->getTitulosMobile($restricaoVendedorCliente, $idVendedores)
        );
    }

    private function getTitulosMobile(bool $restricaoVendedorCliente, $idVendedor)
    {
        $data = TituloFinanceiro::select(
            "titulo_financeiro.*",
            "vendedor.nome AS nome_vendedor"
        )
            ->leftJoin("vendedor", "vendedor.id", "=", "titulo_financeiro.id_vendedor")
            ->distinct();

        if ($restricaoVendedorCliente) {
            $data->join("vendedor_cliente", "vendedor_cliente.id_cliente", "=", "titulo_financeiro.id_cliente")
                ->whereIn("vendedor_cliente.id_vendedor", is_array($idVendedor) ? $idVendedor : [$idVendedor]);
        }

        return $data->get();
    }

    /**
     *
     * Retorna a meta
     * @return null|string
     */
    private
    function _meta()
    {
        return $this->_verificarData(Meta::where("id_vendedor", $this->usuarioLogado()->id_vendedor)->get());
    }

    /**
     * Retorna os detalhes da meta
     * @return null|string
     */
    private
    function _metaDetalhe()
    {
        return $this->_verificarData($this->getMetasDetalhadas($this->usuarioLogado()->id_vendedor));
    }

    private function getMetasDetalhadas($idVendedor)
    {

        $data = MetaDetalhe::select("meta_detalhe.*")
            ->join("meta", "meta.id", "=", "meta_detalhe.id_meta")
            ->where("meta.id_vendedor", $idVendedor)
            ->get();

        return $data;
    }
    /**
     * @return DateTime|null
     * @throws Exception
     */
    private function defineFiltroSupervisorNota()
    {
        $data = new DateTime();
        switch ($this->objetoRestricao->restricao_supervisor_cliente) {
            default:
                $data = NULL;
                break;
            case 4:
                $data = $data->sub(date_interval_create_from_date_string('30 days'));
                break;
            case 3:
                $data = $data->sub(date_interval_create_from_date_string('15 days'));
                break;
        }
        if (!is_null($data)) {
            $data = $data->format("Y-m-d");
        }
        return $data;
    }

    /**
     * RETORNA SOMENTE AS NOTAS PERTENCENTES AO VENDEDOR INFORMADO
     * @param null $idVendedor
     * @param null $dtRetroativa
     * @return array|NULL
     */
    public function getPorVendedor($idVendedor = NULL, $dtRetroativa = NULL)
    {
        $data = NotaFiscalItem::select("nota_fiscal_item.*")
            ->join("nota_fiscal", "nota_fiscal_item.ped_num", "=", "nota_fiscal.ped_num")
            ->join("vendedor_cliente", "vendedor_cliente.id_cliente", "=", "nota_fiscal.id_cliente")
            ->where(function ($query) use ($idVendedor, $dtRetroativa) {
                if (!is_null($dtRetroativa)) $query->where("nota_fiscal.ped_emissao", ">=", $dtRetroativa);
                if (!is_null($idVendedor)) $query->whereIn("vendedor_cliente.id_vendedor", is_array($idVendedor) ? $idVendedor : [$idVendedor]);
            })
            ->distinct()
            ->get();

        return $data;
    }
}
