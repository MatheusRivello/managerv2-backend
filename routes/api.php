<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\AuthMobileController;
use App\Http\Controllers\api\v1\Central\MigrateController;
use App\Http\Controllers\api\v1\Central\UsuarioController;
use App\Http\Controllers\api\v1\SwaggerController;
use App\Http\Controllers\Integracao\IntegradorController;
use App\Http\Controllers\Integracao\PedidoErpController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\api\v1\ProtectedRouteAuth;

// "throttle:20,1" definir limite de requisicoes 
// ->middleware('auth.rotas') adicionar seguranca das rotas

Route::prefix('v1')->group(function () {
    Route::get('rollbar/teste', function () {
        throw new Exception("Exception lançada para teste com Rollbar");
    });
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('resetPassword', [UsuarioController::class, 'sendEmailPassword'])->name('sendEmailPassword');
    Route::patch('resetPassword', [UsuarioController::class, 'resetPassword']);

    Route::middleware([ProtectedRouteAuth::class])->prefix('auth')->group(function () {
        Route::post('me', [AuthController::class, 'me'])->name('me');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');

        Route::middleware([ProtectedRouteAuth::class])->prefix('mobile')->group(function () {
            Route::post('me', [AuthMobileController::class, 'me'])->name('meMobile');
            Route::post('logout', [AuthMobileController::class, 'logout'])->name('logoutMobile');
        });
    });

    Route::post('loginMobile', [AuthMobileController::class, 'login'])->name('loginMobile');
});

Route::middleware([ProtectedRouteAuth::class])->prefix('local')->group(function () {

    //Aviso
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/Aviso.php'));
    //ConfiguraçãoPainel
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/ConfiguracaoPainel.php'));

    //Dispositivo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/Dispositivo.php'));

    //Empresa
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/Empresa.php'));

    //Filial
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/Filial.php'));

    //Grupo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/Grupo.php'));

    //Log
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/Log.php'));

    //LogApi
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/LogApi.php'));

    //LogContato
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/LogContato.php'));

    //LogDispositivo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/LogDispositivo.php'));

    //LogMobile
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/LogMobile.php'));

    //LogSincronismo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/LogSincronismo.php'));

    //LogTenant
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/LogTenant.php'));

    //Perfil
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/Perfil.php'));

    //Relatorio
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/Relatorio.php'));

    //TipoGrafico
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/TipoGrafico.php'));

    //Usuario
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/local/Usuario.php'));
});

Route::middleware([ProtectedRouteAuth::class])->prefix('integracao')->group(function () {
    Route::post('rodar', [IntegradorController::class, 'run']);
    Route::get('log', [IntegradorController::class, 'log']);
    Route::get('timeline', [IntegradorController::class, 'timeline']);
    Route::post('rodar/log', [IntegradorController::class, 'index']);
    Route::post('pedidoerp/body', [PedidoErpController::class, 'mapPedidoToErp']);
    Route::post('pedidoerp/sinc', [PedidoErpController::class, 'sinc']);
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Visita.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Empresa.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Cliente.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Campanha.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Atividade.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Aviso.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/ClientePrazoPagamento.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Fornecedor.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/FormaPrazoPagamento.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/ClienteTabelaPreco.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/IndicadorMargem.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Produto.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Vendedor.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/VendaParametros.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/CondicaoPagamento.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Pedido.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/NotaFiscal.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/integracao/Rota.php'));
});

Route::middleware([ProtectedRouteAuth::class])->prefix('mobile/v2')->group(function () {
    Route::middleware([ProtectedRouteAuth::class])->prefix('zip')->group(function () {
        //Cliente
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Zip/Cliente.php'));

        //Pedido
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Zip/Pedido.php'));

        //Info Financeira
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Zip/Financeiro.php'));

        //Essencial
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Zip/Essencial.php'));

        //Produto
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Zip/Produto.php'));

        //Img
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Zip/Img.php'));
    });

    Route::middleware([ProtectedRouteAuth::class])->prefix('comum')->group(function () {
        //Configuracao Dispositivo
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/ConfigDispositivo.php'));

        //Dispositivo
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/Dispositivo.php'));

        //Vendedor
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/Vendedor.php'));

        //Mix Produto
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/MixProduto.php'));

        //Estoque Cliente
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/EstoqueCliente.php'));

        //Estoque Produto
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/EstoqueProduto.php'));

        //Rastro
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/Rastro.php'));

        //Cliente
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/Cliente.php'));

        //Visita
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/Visita.php'));

        //Pedido
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/Pedido.php'));

        //PedidoV2
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/PedidoV2.php'));

        //Log Dispositivo
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/LogDispositivo.php'));

        //Produto Imagem
        Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Mobile/Comum/ProdutoImagem.php'));
    });
});

Route::middleware([ProtectedRouteAuth::class])->prefix('geral')->group(function () {

    //AcessoMenusApi
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/AcessoMenusApis.php'));

    //CercaEletronica
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/CercaEletronica.php'));

    //ConfigFilial
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/ConfigFilial.php'));

    //ConfigPedidoWeb
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/ConfigPedidoWeb.php'));

    //ConfiguracaoPadrao
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/ConfiguracaoPadrao.php'));

    //IntegracaoCentral
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/IntegracaoCentral.php'));

    //IntegracaoInternaAfv
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/IntegracaoInternaAFV.php'));

    //PeriodoSincronizacao
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/PeriodoSincronizacao.php'));

    //Sincronismo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/Sincronismo.php'));

    //StatusServicoLocal
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/StatusServicoLocal.php'));

    //VersaoAPP
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/VersaoAPP.php'));

    //ConfigIntegrador
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/geral/ConfigIntegrador.php'));
});
Route::middleware([ProtectedRouteAuth::class])->prefix('view')->group(function () {

    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/view/Licenca.php'));
});

Route::middleware([ProtectedRouteAuth::class])->prefix('tenant')->group(function () {

    //ClienteVisitaPlanner
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/UsuarioMudarSenha.php'));

    //FormaPagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/FormaPagamento.php'));

    //Integração elementos
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/IntegracaoElementos.php'));

    //JustificativaAusenciaVendedor
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/JustificativaAusenciaVendedor.php'));

    //JustificativaAusenciaVisita
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/JustificativaAusenciaVisita.php'));

    //Mapa
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/Mapa.php'));

    //Meta
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/Meta.php'));

    //NotaFiscal
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/NotaFiscal.php'));

    //Pedidos
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/Pedidos.php'));

    //Produtos
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/Produto.php'));

    //Seguro
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/Seguro.php'));

    //SetoresDeVisita
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/SetoresDeVisita.php'));

    //TipoPedido
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/TipoPedido.php'));

    //TituloFinanceiro
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/TituloFinanceiro.php'));

    //UsuarioMudarSenha
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/UsuarioMudarSenha.php'));

    //Vendedor
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/Vendedor.php'));

    //Visita Simples 
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/Visita.php'));

    //Visita Monitoramento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/ClienteVisitaPlanner.php'));

    //Cliente
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/Cliente.php'));

    //Motivo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/Motivo.php'));

    //Tabela Preco
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/TabelaPreco.php'));

    //Prazo de pagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/tenant/PrazoDePagamento.php'));
});

Route::middleware([ProtectedRouteAuth::class])->prefix('ws')->group(function () {

    //Atividade
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Atividade.php'));

    //Atividade do cliente
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/AtividadeDoCliente.php'));

    //Aviso
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Aviso.php'));

    //Campanha
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Campanha.php'));

    //CampanhaBeneficio
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/CampanhaBeneficio.php'));

    //CampanhaModalidade
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/CampanhaModalidade.php'));

    //CampanhaParticipante
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/CampanhaParticipante.php'));

    //CampanhaRequisito
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/CampanhaRequisito.php'));

    //Cidade
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Cidade.php'));

    //Cliente
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Cliente.php'));

    //Cliente Endereço
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ClienteEndereco.php'));

    //Cliente Forma de pagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ClienteFormaDePagamento.php'));

    //Cliente Prazo de pagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ClientePrazoDePagamento.php'));

    //Cliente Referencia
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ClienteReferencia.php'));

    //Cliente TabelaGrupo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ClienteTabelaGrupo.php'));

    //Contato
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Contato.php'));

    //Endereco
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Endereco.php'));

    //EstoqueProduto
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/EstoqueProduto.php'));

    //Filial
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Filial.php'));

    //Forma De Pagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/FormaDePagamento.php'));

    //Forma Prazo de Pagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/FormaPrazoDePagamento.php'));

    //Fornecedor
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Fornecedor.php'));

    //Grupo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Grupo.php'));

    //Indicador Margem
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/IndicadorMargem.php'));

    //Integração
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Integracao.php'));

    //Itens da Nota Fiscal
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ItensDaNotaFiscal.php'));

    //Itens do Pedido
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ItensDoPedido.php'));

    //Log
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Log.php'));

    //Meta 
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Meta.php'));

    //Meta Detalhe
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/MetaDetalhe.php'));

    //MixProdutos
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/MixProdutos.php'));

    //Motivo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Motivo.php'));

    //NotaFiscal
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/NotaFiscal.php'));

    //Pedido
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Pedido.php'));

    //Plano de venda
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/PlanoDeVendaDoProduto.php'));

    //Prazo de pagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/PrazoPagamento.php'));

    //Produto
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Produto.php'));

    //ProdutoCashBack
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ProdutoCashBack.php'));

    //ProdutoEmbalagem
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ProdutoEmbalagem.php'));

    //ProdutoIPI
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ProdutoIPI.php'));

    //Produto Imagem
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ImagemProduto.php'));

    //ProdutoST
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ProdutoST.php'));

    //Tabela de preço do produto
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/ProdutoTabelaPreco.php'));

    //Rastreio 
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Rastreio.php'));

    //Regiao
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Regiao.php'));

    //Rota
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Rota.php'));

    //Seguro
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Seguro.php'));

    //Status de Cliente
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/StatusDeCliente.php'));

    //Status de produto
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/StatusDeProduto.php'));

    //SubGrupo
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/SubGrupo.php'));

    //Tabela de preço
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/TabelaDePreco.php'));

    //Tabela de preço do cliente
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/TabelaPrecoDoCliente.php'));

    //Tipo de pedido
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/TipoDePedido.php'));

    //Titulo financeiro
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/TituloFinanceiro.php'));

    //Vendedor
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Vendedor.php'));

    //VendedorCliente
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/VendedorCliente.php'));

    //VendedorProduto
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/VendedorProduto.php'));

    //VendedorTabelaPreco
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/VendedorTabelaPreco.php'));

    //Visita
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/Visita.php'));

    //VisitaPlanner
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/VisitaPlanner.php'));

    //VisitaSetores
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/ws/VisitaSetores.php'));
});

Route::middleware([ProtectedRouteAuth::class])->prefix('servico')->group(function () {
    //Filial
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Filial.php'));

    //Config Filial
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/ConfFilial.php'));

    //Status Pedido
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/StatusPedido.php'));

    //Status Cliente
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/StatusCliente.php'));

    //Status Produto
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/StatusProduto.php'));

    //Forma de Pagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Formapgto.php'));

    //Prazo de Pagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Prazopgto.php'));

    //Motivo nao venda
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/MotivoNaoVenda.php'));

    //Atividade
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Atividade.php'));

    //Grupo de produtos
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/GrupoProdutos.php'));

    //SubGrupo de produtos
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/SubGrupoProdutos.php'));

    //Fornecedor
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Fornecedor.php'));

    //Tabela de preco
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/TabelaPreco.php'));

    //Tipo pedido
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/TipoPedido.php'));

    //Aviso
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Aviso.php'));

    //Campanha
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Campanha.php'));

    //Cliente
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Cliente.php'));

    //Meta
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Meta.php'));

    //Endereco
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Endereco.php'));

    //Forma x Prazo Pagamento
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/FormaPrazopgto.php'));

    //Indicador de margem
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/IndicadorMargem.php'));

    //Imagem
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Imagem.php'));

    //Mix Produtos
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/MixProdutos.php'));

    //Nota Fiscal
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/NotaFiscal.php'));

    //Pedido
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Pedido.php'));

    //Produto
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Produto.php'));

    //Rota
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Rota.php'));

    //Vendedor
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Vendedor.php'));

    //Roteiro
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Roteiro.php'));

    //Servico local
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/ServicoLocal.php'));

    //Titulo Financeiro
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/TituloFinanceiro.php'));

    //Visita
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Visita.php'));

    //Empresa
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Empresa.php'));

    //Configuracao Empresa
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/ConfigEmpresa.php'));

    //Rastro
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/sincronismo/Delphi/Rastro.php'));
});

Route::middleware([ProtectedRouteAuth::class])->prefix('artisan')->group(function () {

    //Rotas de limpeza
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/artisan/RouteClear.php'));
    Route::post('migrate/tenant', [MigrateController::class, 'tenant']);
    Route::post('migrate/data/managerv1/to/managerv2', [MigrateController::class, 'migrateDataV1ToV2']);
});

Route::middleware([ProtectedRouteAuth::class])->prefix('relatorios')->group(function () {
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/relatorios/Visita.php'));
    Route::middleware([ProtectedRouteAuth::class])->group(base_path('routes/relatorios/Pedido.php'));
});

Route::get('/docs', [SwaggerController::class, 'index']);
