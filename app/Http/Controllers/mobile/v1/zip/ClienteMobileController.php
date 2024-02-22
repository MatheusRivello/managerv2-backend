<?php

namespace App\Http\Controllers\mobile\v1\zip;

use App\Http\Controllers\mobile\v1\BaseMobileController;
use Illuminate\Http\Request;

class ClienteMobileController extends BaseMobileController
{
    protected $className;

    public function __construct(Request $request)
    {
        $this->className = "Cliente";
        
        parent::__construct($this->className, $this->usuarioLogado()->mac, $this->usuarioLogado()->versaoApp);
    }

    /**
     *Método principal para retornar todos os dados, seu retorno é via zip
     */
    public function cliente()
    {
        $data = array(
            "cliente.json" => $this->_cliente(),
            "rota.json" => $this->_rota(),
            "endereco.json" => $this->_endereco(),
            "contato.json" => $this->_contato(),
            "vendaplano.json" => $this->_vendaPlano(),
            "vendaplanoproduto.json" => $this->_vendaPlanoProduto(),
            "clientetabela.json" => $this->_clienteTabelaPreco(),
            "clienteforma.json" => $this->_clienteForma(),
            "clienteprazo.json" => $this->_clientePrazo(),
        );

        $this->_downloadZip($this->className, $data);
    }

    /**
     * Retorna os clientes
     * @return null|string
     */
    private function _cliente()
    {
        return $this->_verificarData($this->_retornoDadosPorVendedorOuSupervisor(TABELA_CLIENTE, "cliente.id_retaguarda IS NOT NULL"));
    }

    /**
     * Retorna os endereços
     * @return null|string
     */
    private function _endereco()
    {
        return $this->_verificarData($this->_retornoDadosPorVendedorOuSupervisor(TABELA_ENDERECO));
    }

    /**
     * Retorna os contatos
     * @return null|string
     */
    private function _contato()
    {
        return $this->_verificarData($this->_retornoDadosPorVendedorOuSupervisor(TABELA_CONTATO));
    }

    /**
     * Retorna a venda plano
     * @return null|string
     */
    public function _vendaPlano()
    {
        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_VENDA_PLANO)) {
            return $this->_verificarData($this->_getAllPorTabela(TABELA_VENDA_PLANO));
        } else {
            return $this->_verificarData(NULL);
        }
    }

    /**
     * Retorna venda plano produto
     * @return null|string
     */
    public function _vendaPlanoProduto()
    {
        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_VENDA_PLANO_PRODUTO)) {
            return $this->_verificarData($this->_getAllPorTabela(TABELA_VENDA_PLANO_PRODUTO));
        } else {
            return $this->_verificarData(NULL);
        }
    }

    public function _clienteTabelaPreco()
    {
        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_CLIENTE_TABELA_PRECO)) {
            return $this->_verificarData($this->_getAllPorTabela(TABELA_CLIENTE_TABELA_PRECO));
        } else {
            return $this->_verificarData(NULL);
        }
    }

    public function _clienteForma()
    {
        $retorno = $this->getConfigComValorEsperado("USA_CLIENTE_FORMA", "true");
        if (is_null($retorno)) {
            return $this->_verificarData(NULL);
        } else {
            return $this->_verificarData($this->_getAllPorTabela(TABELA_CLIENTE_FORMA_PGTO));
        }
    }

    public function _clientePrazo()
    {
        $retorno = $this->getConfigComValorEsperado("USA_CLIENTE_PRAZO", "true");

        if (is_null($retorno)) {
            return $this->_verificarData(NULL);
        } else {
            return $this->_verificarData($this->_getAllPorTabela(TABELA_CLIENTE_PRAZO_PGTO));
        }
    }

    public function _rota()
    {
        if ($this->verificaExistenciaDaTabela($this->usuarioLogado()->empresa->bd_nome, TABELA_ROTA)) {
            return $this->_verificarData($this->_getAllPorTabela(TABELA_ROTA));
        } else {
            return $this->_verificarData(NULL);
        }
    }
}
