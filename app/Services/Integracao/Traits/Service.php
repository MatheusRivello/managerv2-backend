<?php

namespace App\Services\Integracao\Traits;

trait Service {
    private $empresa;

    public function __construct() {}

    public function getAllServicesName() {
        return [
            0 => ['FilialService'], // Empresa
            1 => ['StatusClienteService'],
            2 => ['StatusProdutoService'],
            3 => ['StatusPedidoService'],
            4 => ['ClienteService'],
            5 => ['EnderecoService'],
            6 => ['ContatoService'],
            7 => ['FormaPagamentoService'],
            8 => ['PrazoPagamentoService'],
            9 => ['ClienteFormaPgtoService'],
            10 => ['ClientePrazoPgtoService'],
            11 => ['MotivoService'],
            12 => ['VendedorService'],
            13 => ['VendedorClienteService'],
            14 => ['AtividadeService'],
            15 => ['ProdutoGrupoService'], // Grupo
            16 => ['ProdutoSubGrupoService'], // Familia
            17 => ['FornecedorService'],
            18 => ['ProdutoService'],
            19 => ['ProdutoTabelaPrecoService'],
            20 => ['ProdutoTabelaItensService'],
            21 => ['ProdutoSTService'],
            22 => ['ProdutoIPIService'],
            23 => [
                'EmpresaImagemService',
                'ProdutoImagemService',
            ],
            24 => ['TipoPedidoService'],
            25 => [
                'NotaFiscalService',
                'NotaFiscalItemService',
            ],
            26 => ['ProdutoDescontoQuantidadeService'],
            27 => ['TituloFinanceiroService'],
            28 => ['FormaPrazoPagamentoService'],
            29 => ['IndicadorMargemService'],
            30 => ['ConfigFilialService'], // Configuracoes
            31 => [
                'MetaService',
                'MetaDetalheService'
            ],
            32 => ['VisitaService'],
            33 => ['AvisoService'],
            34 => ['VendedorProtabelaPrecoService'],
            35 => ['VendedorProdutoService'],
            36 => ['MixProdutoService'],
            37 => ['37'],
            38 => [
                'ClienteTabelaGrupoService',
                'ClienteTabelaPrecoService'
            ],
            39 => ['39'],
            40 => ['VisitaService'],
            41 => ['41'],
            42 => ['42'],
            43 => ['43'],
            44 => ['44'],
            45 => ['ProdutoEmbalagemService'],
            46 => ['ProdutoEstoqueService'],
            47 => [
                'VendaPlanoService',
                'VendaPlanoProdutoService',
            ],
            48 => ['VendedorPrazoService'],
            49 => [
                'CampanhaService',
                'CampanhaModalidadeService',
                'CampanhaBeneficioService',
                'CampanhaParticipanteService',
                'CampanhaRequisitoService',
            ],
            50 => ['RotaService'],
        ];
    }
}
