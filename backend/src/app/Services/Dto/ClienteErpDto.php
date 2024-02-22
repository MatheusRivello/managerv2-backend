<?php

namespace App\Services\Dto;

use App\Models\Tenant\Atividade;
use App\Models\Tenant\Cliente;
use App\Models\Tenant\FormaPagamento;
use App\Models\Tenant\PrazoPagamento;

class ClienteErpDto {
    public $atividade;
    public $contatos;
    public $cpf_cnpj;
    public $email;
    public $emp_cod;
    public $enderecos;
    public $inscricao_estadual;
    public $inscricao_municipal;
    public $modo_cobranca;
    public $nome_fantasia;
    public $prazo_pagamento;
    public $razao_social;
    public $tabela_preco;
    public $tipo_pessoa;
    public $vendedor;

    public function __construct(Cliente $cliente) {
        $atividade = Atividade::find($cliente->id_atividade);
        $modoCobranca = FormaPagamento::find($cliente->id_forma_pgto);
        $prazoPgto = PrazoPagamento::find($cliente->id_prazo_pgto);

        $this->atividade = empty($atividade->id_retaguarda) ? null : $atividade->id_retaguarda;
        $this->cpf_cnpj = $cliente->cnpj;
        $this->email = $cliente->email;
        $this->emp_cod = $cliente->id_filial;
        $this->inscricao_estadual = $cliente->inscricao_rg;
        $this->inscricao_municipal = $cliente->inscricao_municipal;
        $this->modo_cobranca = empty($modoCobranca->id_retaguarda) ? null : $modoCobranca->id_retaguarda;
        $this->nome_fantasia = $cliente->nome_fantasia;
        $this->prazo_pagamento = empty($prazoPgto->id_retaguarda) ? null : $prazoPgto->id_retaguarda;
        $this->razao_social = $cliente->razao_social;
        // app salva id_retaguarda da tabela de preço
        $this->tabela_preco = $cliente->id_tabela_preco;
        $this->tipo_pessoa = intval($cliente->tipo);
        $this->vendedor = $cliente->ven_cod;
        // contato será duplicado se tiver apenas um, pois um segundo contato é obrigatório na API
        $this->contatos = count($cliente->contatos) == 1 ? [$cliente->contatos[0], $cliente->contatos[0]] : $cliente->contatos;
        $this->enderecos = $cliente->enderecos;
    }
}
