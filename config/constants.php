<?php
// Constants Gerais 
define("VERSAO_APP", "0.0.6");
define("REGISTRO_SALVO", "Registro salvo com sucesso");
define("REGISTRO_EXCLUIDO", "Registro excluido com sucesso");
define("REGISTRO_NAO_ENCONTRADO", "Registro não encontrado");
define("ID_NAO_INFORMADO", "ID não informado");
define("DADO_NAO_INFORMADO", "OPS! Algum dado não informado corretamente");
define("ERROR_ID_INTERNO_ELEMENTOS", "Não possível encontrar nenhum registro (Cliente ou Produto) com este dado informado.");
define("REGISTRO_DUPLICADO", "Já existe um cadastro com este id_interno, tipo e integrador.");
define("ERRO_CONSULTA", "Erro na consulta, verifique os parametros");
define("ID_NAO_ENCONTRADO", "Este ID não foi encontrado");
define("CONFIGURACAO_NAO_ENCONTRADA", "Configuração padrão não encontrada.");
define("MAC_NAO_ENCONTRADO", "Este ID ou MAC não foi encontro");
define("HORARIO_NAO_ENCONTRADA", "Horario padrão não encontrado");
define("ERRO_NA_CONSULTA", "Erro na consulta, verifique os parametros");
define("SENHA_GERADA_SUCESSO", "Senha gerada com sucesso!");
define("SEM_EMPRESA_RELACIONADA", "Usuário não possui empresa relacionada.");
define("TOKEN_INVALIDO", "Token do dispositivo inválido ou vendedor inativo!");
define("DELETE_FILE_ERROR", "Erro ao excluir o arquivo, tente novamente.");
define("FILE_NOT_FOUND", "Arquivo não encontrado.");
define("EMAIL_USER_NOT_FOUND", "Nenhum usuário encontrado com o email informado.");
define("TOKEN_INVALID", "Token Invalido.");
define("TOKEN_EXPIRED", "Expirado, solicite um novo token.");
define("TOKEN_USED", "Token Invalido, pois já foi utilizado.");
define("PASSWORD_CHANGED", "Senha alterada.");
define("EMAIL_ENVIADO", "Enviado para o email informado.");
define("EXISTE_RELACIONAMENTOS", "Este registro não pode ser excluído. Existem registros atrelados a este ID.");
define("EXISTE_REGISTRO", "Já há registros com esses dados");
define("ERRO_CONSULTA_DINAMICA", "Erro ao consultar este id, contate o suporte.");
define("ERRO_VARIAVEL_INDEFINIDA", "Os campos obrigatórios não foram informados, verifique");
define("ERRO_DE_INTEGRIDADE", "Não é possível fazer a mudança do registro pois o mesmo se enontra em outras tabelas");
define("REGISTRO_MODIFICADO", "Registro modificado!");
define("HA_REGISTRO_ATRELADO_A_ESTE_ID", "Já existe registro atrelado a este id_retaguarda");

/**NOMES TABELAS DO BANCO DE DADOS DAS EMPRESAS*/
define("TABELA_ATIVIDADE", "atividade");
define("TABELA_AVISO", "aviso");
define("TABELA_CIDADE", "cidade");
define("TABELA_CLIENTE", "cliente");
define("TABELA_CLIENTE_FORMA_PGTO", "cliente_forma_pgto");
define("TABELA_CLIENTE_PRAZO_PGTO", "cliente_prazo_pgto");
define("TABELA_CLIENTE_TABELA_GRUPO", "cliente_tabela_grupo");
define("TABELA_CLIENTE_TABELA_PRECO", "cliente_tabela_preco");
define("TABELA_CONFIGURACAO_FILIAL", "configuracao_filial");
define("TABELA_CONTATO", "contato");
define("TABELA_ENDERECO", "endereco");
define("TABELA_FILIAL", "filial");
define("TABELA_FORMA_PAGAMENTO", "forma_pagamento");
define("TABELA_FORMA_PRAZO_PGTO", "forma_prazo_pgto");
define("TABELA_GRUPO", "grupo");
define("TABELA_INDICADOR_MARGEM", "indicador_margem");
define("TABELA_LOG", "log");
define("TABELA_META", "meta");
define("TABELA_META_DETALHE", "meta_detalhe");
define("TABELA_MIX_PRODUTO", "mix_produto");
define("TABELA_MOTIVO", "motivo");
define("TABELA_NOTA_FISCAL", "nota_fiscal");
define("TABELA_NOTA_FISCAL_ITEM", "nota_fiscal_item");
define("TABELA_PEDIDO", "pedido");
define("TABELA_PEDIDO_ITEM", "pedido_item");
define("TABELA_PRAZO_PAGAMENTO", "prazo_pagamento");
define("TABELA_PRODUTO", "produto");
define("TABELA_PRODUTO_DESCTO_QTD", "produto_descto_qtd");
define("TABELA_PRODUTO_IMAGEM", "produto_imagem");
define("TABELA_PRODUTO_IPI", "produto_ipi");
define("TABELA_PRODUTO_ST", "produto_st");
define("TABELA_PROTABELA_ITENS", "protabela_itens");
define("TABELA_PROTABELA_PRECO", "protabela_preco");
define("TABELA_RASTRO", "rastro");
define("TABELA_STATUS_CLIENTE", "status_cliente");
define("TABELA_STATUS_PRODUTO", "status_produto");
define("TABELA_SUBGRUPO", "subgrupo");
define("TABELA_TIPO_PEDIDO", "tipo_pedido");
define("TABELA_TITULO_FINANCEIRO", "titulo_financeiro");
define("TABELA_VENDEDOR", "vendedor");
define("TABELA_VENDEDOR_CLIENTE", "vendedor_cliente");
define("TABELA_VENDEDOR_PRODUTO", "vendedor_produto");
define("TABELA_VENDEDOR_PROTABELA_PRECO", "vendedor_protabelapreco");
define("TABELA_VISITA", "visita");
define("TABELA_STATUS_PEDIDO", "status_pedido");
define("TABELA_FORNECEDOR", "fornecedor");
define("TABELA_PRODUTO_EMBALAGEM", "produto_embalagem");
define("TABELA_PRODUTO_ESTOQUE", "produto_estoque");
define("TABELA_VENDA_PLANO", "venda_plano");
define("TABELA_VENDA_PLANO_PRODUTO", "venda_plano_produto");
define("TABELA_VENDEDOR_PRAZO", "vendedor_prazo");
define("TABELA_CAMPANHA_MODALIDADE", "campanha_modalidade");
define("TABELA_CAMPANHA_PARTICIPANTE", "campanha_participante");
define("TABELA_CAMPANHA_REQUISITO", "campanha_requisito");
define("TABELA_CAMPANHA_BENEFICIO", "campanha_beneficio");
define("TABELA_CAMPANHA", "campanha");
define("TABELA_SEGURO", "seguro");
define("TABELA_ROTA", "rota");

define("TABELA_CABECALHO_REQ_ZIP", "cabecalho_requisicao_zip");
define("TABELA_SERVICO_LOCAL", "servico_local");
define("TABELA_EMPRESA", "empresa");
define("TABELA_CONFIGURACAO_EMPRESA", "configuracao_empresa");


/**NAMESPACE DAS REGRAS*/
define("RULE_TIPO_EMPRESA", "App\Rules\Config\TipoEmpresaRule");
define("RULE_TIPO_PERMISSAO", "App\Rules\Config\TipoPermissaoRule");
define("RULE_AVISO_CENTRAL", "App\Rules\Api\Central\AvisoRule");
define("RULE_CERCA_ELETRONICA_CENTRAL", "App\Rules\Api\Central\CercaEletronicaRule");
define("RULE_CONFIG_PADRAO_CENTRAL", "App\Rules\Api\Central\ConfigPadraoRule");
define("RULE_DISPOSITIVO_CENTRAL", "App\Rules\Api\Central\DispositivoRule");
define("RULE_EMPRESA_CENTRAL", "App\Rules\Api\Central\EmpresaRule");
define("RULE_INTEGRACAO_CENTRAL", "App\Rules\Api\Central\IntegracaoRule");
define("RULE_PERFIL_CENTRAL", "App\Rules\Api\Central\PerfilRule");
define("RULE_SINCRONISMO_CENTRAL", "App\Rules\Api\Central\SincronismoRule");
define("RULE_USUARIO_CENTRAL", "App\Rules\Api\Central\UsuarioRule");
define("RULE_VERSAO_CENTRAL", "App\Rules\Api\Central\VersaoRule");
define("RULE_GRUPO_RELATORIO", "App\Rules\Config\GrupoRelatorioRule");
define("RULE_TIPO_GRAFICO", "App\Rules\Config\TipoGraficoRule");
define("RULE_RELATORIO", "App\Rules\Config\RelatorioRule");
define("RULE_ACESSO_API", "App\Rules\Api\Central\AcessoApiRule");
define("RULE_ACESSO_MENU", "App\Rules\Api\Central\AcessoMenuRule");
define("RULE_LOG", "App\Rules\Api\Central\LogRule");
define("RULE_LOG_CONTATO", "App\Rules\Api\Central\LogContatoRule");
define("RULE_LOG_MOBILE", "App\Rules\Api\Central\LogMobileRule");
define("RULE_LOG_SINCRONISMO", "App\Rules\Api\Central\LogSincronismoRule");
define("RULE_LOG_API", "App\Rules\Api\Central\LogApiRule");
define("RULE_LOG_DISPOSITIVO", "App\Rules\Api\Central\LogDispositivoRule");
define("RULE_FILIAL_TENANT", "App\Rules\Api\Tenant\FilialTenantRule");
define("RULE_INTEGRACAO_TENANT", "App\Rules\Api\Tenant\IntegracaoTenantRule");
define("RULE_LOG_FILIAL", "App\Rules\Api\Tenant\LogTenantRule");
define("RULE_CONFIG_PW_TENANT", "App\Rules\Api\Tenant\Config\ConfigPedWebTenantRule");
define("RULE_CONFIG_FILIAL_TENANT", "App\Rules\Api\Tenant\Config\ConfigFilialTenantRule");
define("RULE_SEGURO_TENANT", "App\Rules\Api\Tenant\SeguroTenantRule");
define("RULE_USUARIO_TENANT", "App\Rules\Api\Tenant\UsuarioTenantRule");
define("RULE_PEDIDO_TENANT", "App\Rules\Api\Tenant\PedidoTenantRule");
define("RULE_TERMO_PW_TENANT", "App\Rules\Config\TermoPwRule");
define("RULE_TITULO_FINANCEIRO_API_EXTERNA", "App\Rules\Api\Externa\TituloFinanceiroExternaRule");
define("RULE_CIDADE_EXTERNA", "App\Rules\Api\Externa\CidadeExternaRule");
define("RULE_FORMA_PGTO_EXTERNA", "App\Rules\Api\Externa\FormaPgtoExternaRule");
define("RULE_TABELA_PRECO_EXTERNA", "App\Rules\Api\Externa\TabelaPrecoExternaRule");
define("RULE_FORNECEDOR_EXTERNA", "App\Rules\Api\Externa\FornecedorExternaRule");
define("RULE_STATUS_DE_PRODUTO_EXTERNA", "App\Rules\Api\Externa\StatusDeProdutoExternaRule");
define("RULE_NOTA_FISCAL_EXTERNA", "App\Rules\Api\Externa\NotaFiscalExternaRule");
define("RULE_GRUPO_EXTERNA", "App\Rules\Api\Externa\GrupoExternaRule");
define("RULE_PRAZO_DE_PAGAMENTO_EXTERNA", "App\Rules\Api\Externa\PrazoDePagamentoExternaRule");
define("RULE_SUBGRUPO_EXTERNA", "App\Rules\Api\Externa\SubGrupoExternaRule");
define("RULE_PRODUTO_EXTERNA", "App\Rules\Api\Externa\ProdutoExternaRule");
define("RULE_CLIENTE_EXTERNA", "App\Rules\Api\Externa\ClienteExternaRule");
define("RULE_MOTIVO_API_EXTERNA", "App\Rules\Api\Externa\MotivoExternaRule");
define("RULE_CAMPANHA_EXTERNA", "App\Rules\Api\Externa\CampanhaExternaRule");
define("RULE_CAMPANHA_MODALIDADE_EXTERNA", "App\Rules\Api\Externa\CampanhaModalidadeExternaRule");
define("RULE_CLIENTE_ENDERECO_EXTERNA", "App\Rules\Api\Externa\ClienteEnderecoExternaRule");
define("RULE_CAMPANHA_PARTICIPANTE_EXTERNA", "App\Rules\Api\Externa\CampanhaParticipanteExternaRule");
define("RULE_PRODUTO_ESTOQUE_EXTERNA", "App\Rules\Api\Externa\ProdutoEstoqueExternaRule");
define("RULE_STATUS_DE_CLIENTE_EXTERNA", "App\Rules\Api\Externa\StatusDeClienteRule");
define("RULE_CLIENTE_ATIVIDADE_EXTERNA", "App\Rules\Api\Externa\ClienteAtividadeExternaRule");
define("RULE_CAMPANHA_REQUISITO_EXTERNA", "App\Rules\Api\Externa\CampanhaRequisitoExternaRule");
define("RULE_CLIENTE_FORMA_DE_PAGAMENTO", "App\Rules\Api\Externa\ClienteFormadePagamentoExternaRule");
define("RULE_TIPO_PEDIDO_EXTERNA", "App\Rules\Api\Externa\TipoPedidoExternaRule");
define("RULE_NOTA_FISCAL_ITEM_EXTERNA", "App\Rules\Api\Externa\NotaFiscalItemExternaRule");
define("RULE_CLIENTE_TABELA_GRUPO_EXTERNA", "App\Rules\Api\Externa\ClienteTabelaGrupoExternaRule");
define("RULE_CLIENTE_FORMA_DE_PAGAMENTO_EXTERNA", "App\Rules\Api\Externa\ClienteFormadePagamentoExternaRule");
define("RULE_CLIENTE_PRAZO_PAGAMENTO_EXTERNA", "App\Rules\Api\Externa\ClientePrazoPagamentoExternaRule");
define("RULE_CONTATO_EXTERNA", "App\Rules\Api\Externa\ContatoExternaRule");
define("RULE_FORMA_PRAZO_DE_PAGAMENTO_EXTERNA", "App\Rules\Api\Externa\FormaPrazoDePagamentoExternaRule");
define("RULE_ENDERECO_EXTERNA", "App\Rules\Api\Externa\EnderecoExternaRule");
define("RULE_META_EXTERNA", "App\Rules\Api\Externa\MetaExternaRule");
define("RULE_PRODUTO_EMBALAGEM_EXTERNA", "App\Rules\Api\Externa\ProdutoEmbalagemExternaRule");
define("RULE_META_DETALHE_EXTERNA", "App\Rules\Api\Externa\MetaDetalheExternaRule");
define("RULE_INDICADOR_DE_MARGEM_EXTERNA", "App\Rules\Api\Externa\IndicadorDeMargemexternaRule");
define("RULE_FILIAL_EXTERNA", "App\Rules\Api\Externa\FilialExternaRule");
define("RULE_PRODUTO_ST_EXTERNA", "App\Rules\Api\Externa\ProdutoSTExternaRule");
define("RULE_REGIAO_EXTERNA", "App\Rules\Api\Externa\RegiaoExternaRule");
define("RULE_PRODUTO_Ipi_EXTERNA", "App\Rules\Api\Externa\ProdutoIpiExternaRule");
define("RULE_PRODUTO_TABELA_EXTERNA", "App\Rules\Api\Externa\ProdutoTabelaExternaRule");
define("RULE_SEGURO_EXTERNA", "App\Rules\Api\Externa\SeguroExternaRule");
define("RULE_AVISO_EXTERNA", "App\Rules\Api\Externa\AvisoExternaRule");
define("RULE_ATIVIDADE_EXTERNA", "App\Rules\Api\Externa\AtividadeExternaRule");
define("RULE_CAMPANHA_BENEFICIO_EXTERNA", "App\Rules\Api\Externa\CampanhaBeneficioExternaRule");
define("RULE_CLIENTE_REFERENCIA_EXTERNA", "App\Rules\Api\Externa\ClienteReferenciaRule");
define("RULE_PEDIDO_EXTERNA", "App\Rules\Api\Externa\PedidoExternaRule");
define("RULE_CLIENTE_TABELA_PRECO_EXTERNA", "App\Rules\Api\Externa\ClienteTabelaPrecoExternaRule");
define("RULE_INTEGRACAO_EXTERNA", "App\Rules\Api\Externa\IntegracaoExternaRule");
define("RULE_MIX_PRODUTO_EXTERNA", "App\Rules\Api\Externa\MixProdutoExternaRule");
define("RULE_PEDIDO_ITEM_EXTERNA", "App\Rules\Api\Externa\PedidoItemExternaRule");
define("RULE_LOG_EXTERNA", "App\Rules\Api\Externa\LogExternaRule");
define("RULE_VISITA_EXTERNA", "App\Rules\Api\Externa\VisitaExternaRule");
define("RULE_RASTRO_EXTERNA", "App\Rules\Api\Externa\RastroExternaRule");
define("RULE_VENDEDOR_EXTERNA", "App\Rules\Api\Externa\VendedorExternaRule");
define("RULE_VENDEDOR_X_CLIENTE_EXTERNA", "App\Rules\Api\Externa\VendedorClienteExternaRule");
define("RULE_ROTA_EXTERNA", "App\Rules\Api\Externa\RotaExternaRule");
define("RULE_VENDA_PLANO_X_PRODUTO_EXTERNA", "App\Rules\Api\Externa\VendaPlanoxVendedorRule");
define("RULE_VENDA_PLANO", "App\Rules\Api\Externa\VendaPlanoExternaRule");
define("RULE_VENDEDOR_PRODUTO", "App\Rules\Api\Externa\VendedorxProdutoExternaRule");
define("RULE_VENDEDOR_X_TABELA_PRECO", "App\Rules\Api\Externa\VendedorTabelaPrecoRule");
define("RULE_JUSTIFICATIVA_VISITA", "App\Rules\Api\Tenant\JustificativaVisitaRule");
define("RULE_JUSTIFICATIVA_VENDEDOR", "App\Rules\Api\Tenant\JustificativaVendedorRule");
define("RULE_VISITA_SETORES", "App\Rules\Api\Tenant\VisitaSetoresRule");
define("RULE_VISITA_PLANNER", "App\Rules\Api\Tenant\VisitaPlannerRule");
define("RULE_PRODUTO_CASHBACK", "App\Rules\Api\Externa\ProdutoCashBackExternaRule");
define("RULE_PERIODO_SINCRONIZACAO", "App\Rules\Api\Central\PeriodoSincronizacaoRule");
define("RULE_INTEGRACAO_INTERNA_AFV", "App\Rules\Api\Central\IntegracaoProgamadaRule");
define("RULE_CLIENTE_SERVICO", "App\Rules\Api\Servico\ClienteServiceRule");
define("RULE_CONTATO_SERVICO", "App\Rules\Api\Servico\ContatoServiceRule");
define("RULE_ENDERECO_SERVICO", "App\Rules\Api\Servico\EnderecoServiceRule");
define("RULE_REFERENCIA_SERVICO", "App\Rules\Api\Servico\ReferenciaServiceRule");
define("RULE_VISITA_SERVICO", "App\Rules\Api\Servico\VisitaServiceRule");
define("RULE_PEDIDO_CABECALHO_SERVICO", "App\Rules\Api\Servico\PedidoCabecalhoServiceRule");
define("RULE_PEDIDO_ITENS_SERVICO", "App\Rules\Api\Servico\PedidoItemServiceRule");
define("RULE_IMAGEM_EXTERNA", "App\Rules\Api\Externa\ProdutoImagemExternaRule");
define("RULE_VISITA", "App\Rules\Api\Tenant\VisitaTenantRule");
define("RULE_RELATORIO_PEDIDO", "App\Rules\Api\Relatorios\Pedido");
define("RULE_CONFIG_INTEGRADOR", "App\Rules\Api\Central\ConfigIntegradorRule");

/**Constant de tipo de permissão*/
define("TIPO_PERMISSAO_CADASTRAR", 1);
define("TIPO_PERMISSAO_VISUALIZAR", 2);
define("TIPO_PERMISSAO_EDITAR", 3);
define("TIPO_PERMISSAO_GERAR", 4);
define("TIPO_PERMISSAO_PEDIDOS_POR_COODENADAS", 5);
define("TIPO_PERMISSAO_NOTIFICAR_NOVA_VERSAO", 6);
define("TIPO_PERMISSAO_EXCLUIR", 7);
define("TIPO_PERMISSAO_REPLICAR", 8);
define("TIPO_PERMISSAO_VISUALIZAR_VENDEDORES", 9);
define("TIPO_PERMISSAO_VISUALIZAR_PEDIDOS", 10);
define("TIPO_PERMISSAO_VISUALIZAR_MARGEM_MARKUP", 11);
define("TIPO_PERMISSAO_VENDAS_EXTERNAS", 12);

// Constants para o FCM
define("FCM_API_URL", "https://fcm.googleapis.com/fcm/send");
define("FCM_API_KEY", "AIzaSyCWyo_ZJ2Kj52qVFd7oDv9S0MKO9drawe0");

/**Constants para upload de arquivos*/
define("DRIVE_DEFAULT", "public");

define("BD_CENTRAL", TRUE);
define("BD_TENANT", FALSE);

define("TIPO_IMAGEM", 1);
define("TIPO_DOCUMENTO", 2);
define("TIPO_VIDEO", 3);
define("OUTROS_TIPOS", 4);
define("TIPO_ZIP", 5);
define("TIPO_SERVICO_ZIP", 6);

define("PATH_STORAGE", "public");
define("PATH_IMAGEM", "imagem");
define("PATH_DOCUMENTO", "documento");
define("PATH_VIDEO", "video");
define("PATH_OUTROS", "outros");
define("PATH_ZIP", "zip");

define("EXTENSAO_IMAGEM", "jpg");


define("TIPO_EMPRESA_SIG2000", 1);
define("TIPO_EMPRESA_EMPRESA", 2);

define("TIPO_PERFIL_EMPRESA_SUPERVISOR", 1);
define("TIPO_PERFIL_EMPRESA_GERENTE", 2);
define("TIPO_PERFIL_EMPRESA_COMUM", 3);
define("TIPO_PERFIL_SIG2000_COMUM", 4);

define("TIPO_ACESSO_COMUM", 0);
define("TIPO_ACESSO_RESPONSAVEL", 1);
define("TIPO_ACESSO_SIG2000_ADMIN", 100);

define("PREFIXO_ON_CONTROLLER", "prefixoOnController");

define("PREFIXO_TENANT", "empresa");

define("TIPO_ELEMENTO_CLIENTE", "App\Models\Tenant\Cliente");
define("TIPO_ELEMENTO_PRODUTO", "App\Models\Tenant\Produto");

define("STATUS_ABERTO_VISITA", 0);
define("STATUS_SEM_VISITA", 1);
define("STATUS_SEM_VENDA_VISITA", 2);
define("STATUS_COM_PEDIDO_VISITA", 3);

define("RAZAO_SOCIAL_CLIENTE", "0");
define("NOME_FANTASIA_CLIENTE", "1");
define("DATA_PEDIDO", "1");

define("TOTALMENTE_ATENDIDO_NOTA", 1);
define("PARCIALMENTE_ATENDIDO_NOTA", 2);
define("NAO_ATENDIDO_NOTA", 3);
define("DEVOLUCAO_NOTA", 4);
define("BONIFICACAO_NOTA", 5);
define("AGUARDANDO_FATURAMENTO_NOTA", 6);

// Tipo Retorno Margem Markup
define("TODOS_TIPO_RETORNO", 0);
define("VENDEDOR_TIPO_RETORNO", 1);
define("CLIENTE_TIPO_RETORNO", 2);
define("ATIVIDADE_TIPO_RETORNO", 3);
define("PRODUTO_TIPO_RETORNO", 4);
define("GRUPO_TIPO_RETORNO", 5);
define("SUBGRUPO_TIPO_RETORNO", 6);
define("MODO_DE_COBRANCA_TIPO_RETORNO", 7);
define("PRAZO_DE_COBRANCA_TIPO_RETORNO", 8);

//Caminho url front
define("BASE_URL_FRONTEND", "https://afv.sig2000.com.br/");
define("RECOVERY_URL_FRONTEND", "recuperarsenha/");

define("TOKEN_UTLIZADO", 1);

define('NOME_BANCO_CENTRAL', 'central_afv'); //BANCO PRODUCAO

//Status Pedidos
define("PEDIDO_AGUARDANDO_SINCRONIZADO", 0);
define("PEDIDO_SINCRONIZADO", 1);
define("PEDIDO_PENDENTE_APROVACAO", 5);
define("PEDIDO_REPROVADOS", 7);
define("PEDIDO_AGUARDANDO_PAGAMENTO", 10); // PGS
define("PEDIDO_PAGAMENTO_CONFIRMADO", 11); // PGS
define("PEDIDO_PAGAMENTO_NEGADO", 13); // PGS

/**Constantes de mensagens*/
//ERROS
define("PACOTE_NAO_INSERIDO", "PACOTE NAO INSERIDO TENTE NOVAMENTE");
define("PACOTE_NAO_ATUALIZADO", "PACOTE NAO ATUALIZADO TENTE NOVAMENTE");
define("ERRO_INATIVAR_DADOS", "Houve uma falha ao inativar todos os dados");
define("ERRO_LIMPAR_TABELA", "Houve uma falha ao limpar tabela");
define("ERRO_REGISTRO_NAO_LOCALIZADO", "Nenhum registro localizado");
define("ERRO_RETORNAR_DADOS_EMPRESA", "Nao foi possivel encontrar dados de nenhuma empresa.");
define("ERRO_ARRAY_VAZIO", "Array vazio ou inválido");
define("ERRO_PACOTE_VAZIO", "Pacote vazio nao foi possivel, atualizar os dados deste pacote");
define("MAC_INVALIDO", "Mac inválido");
define("ERRO_DE_EXCECAO", "Erro de exceção");
define("SINCRONIZANDO_NUVEM", "Nuvem esta sendo sincronizada");
define("NAO_HA_DADOS_DO_BANCO_COM_MAC", "Não foi encontrado nenhum registro com o mac informado para estabelecer uma conexão com banco de dados");

/**Constantes do servico*/
define("CLASS_SERVICE", "_service");
define("HTTP_INTERNAL_SERVER_ERROR", 500);
define("HTTP_OK", 200);
define("HTTP_CREATED", 201);
define("HTTP_ACCEPTED", 202);
define("HTTP_NON_AUTHORITATIVE_INFORMATION", 203);
define("HTTP_NO_CONTENT", 204);
define("HTTP_NOT_FOUND", 404);
define("HTTP_NOT_ACCEPTABLE", 406);
define("HTTP_UNPROCESSABLE_ENTITY", 422);

/*Central e Tenant*/
define("MODEL_CENTRAL", 0);
define("MODEL_TENANT", 1);
define("CAMINHO_CENTRAL", "App\Models\Central");
define("CAMINHO_TENANT", "App\Models\Tenant");

//Status Corpo Requisicao
define("STATUS_PACOTE_RECEBENDO", 1);
define("STATUS_PACOTE_RECEBIDOS", 2);
define("STATUS_PACOTE_EXECUTANDO", 3);
define("STATUS_PACOTE_FINALIZADO", 4);
define("STATUS_PACOTE_ERRO", 5);
define("STATUS_PERIODO_NAO_BAIXAR", 0);
define("STATUS_PERIODO_BAIXAR", 1);
define("STATUS_PERIODO_RECEBENDO_ZIP", 2);
define("STATUS_PERIODO_EXECUTANDO", 3);

//Status Cabecalho Requisicao
define("STATUS_ENVIANDO_PACOTE", 1);
define("STATUS_EXECUTANDO_PACOTE", 2);
define("STATUS_FINALIZADO_EXECUCAO_PACOTE", 3);
define("STATUS_ERRO_CRITICO_PACOTE", 4);

//Constants tabela ConfigEmpresa (central)
define("STATUS_CONF_EMPRESA_INATIVO", 0);
define("STATUS_CONF_EMPRESA_ATIVO", 1);
define("INTEGRACAO_ONLINE", 0);
define("INTEGRACAO_PROGRAMADA", 1);

//Tipos de Requsicao - Tabela Periodo Sincronizacao
define("TIPO_REQ_CRITICA", 1);
define("TIPO_REQ_ONLINE", 2);
define("TIPO_REQ_PROGRAMADA", 3);

define("CAMINHO_DEFAULT_CONTROLLER_DELPHI", "App\Http\Controllers\servico" . DIRECTORY_SEPARATOR . "v1\ERP");

//Permissão
define("ERRO_PERMISSAO", "Você não possui permissão suficientes");

define("ERRO_INSERIR_SERVICO", "Houve um erro ao inserir os dados");
define("ERRO_ATUALIZAR_SERVICO", "Houve um erro ao atualizar os dados");
define("ERRO_DELETAR_SERVICO", "Houve um erro ao deletar os dados");
define("ERRO_CONSULTA_SERVICO", "Houve um erro ao consultar os dados");

define("LIMIT_INSERSAO_BATCH_1000", 1000);
define("LIMIT_INSERSAO_BATCH_10000", 10000);

define("NUVEM_BLOQUEADA", 0);
define("NUVEM_LIBERADA", 1);

define("EMAIL_ERROS_SINCRONISMO", 'maycon@sig2000.com.br');

//Visitas Status
define("STATUS_VISITA_ABERTO", 0);
define("STATUS_VISITA_SEM_VISITA", 1);
define("STATUS_VISITA_SEM_VENDA", 2);
define("STATUS_VISITA_COM_VENDA", 3);
define("STATUS_VISITA_FINALIZADA", 5);
define("STATUS_VISITA_FINALIZADA_FORA_DO_RAIO", 6);
define("STATUS_VISITA_FINALIZADA_AFV", 7);
define('ORIGEM_CADASTRO_ERP', 1);
define('ORIGEM_CADASTRO_AFV', 0);
define('VISITA_SINC_ERP', 0);
define('VISITA_NAO_SINC_ERP', 1);

define('LICENCA_NAO_EXISTE', 0);
define('LICENCA_INVALIDA', 1);
define('LICENCA_VALIDA', 2);
define('LICENCA_BLOQUEADA', 3);
define('LICENCA_BLOQUEADA_ATUALIZACAO', 4);

define('OPCAO_SIM', 1);
define('OPCAO_NAO', 0);

define("CAMINHO_PADRAO_STORAGE", env("CAMINHO_PADRAO_STORAGE", '/var/www/afv2backend.sig2000.com.br/html/public/arquivos/'));
define("URL_BACKEND", env("APP_URL", 'https://afv2backend.sig2000.com.br'));

define("ERRO_INSERIR", "Houve um erro ao inserir os dados");
define("ERRO_ATUALIZAR", "Houve um erro ao atualizar os dados");
define("ERRO_DELETAR", "Houve um erro ao deletar os dados");

define("ERRO_MAC_INVALIDO", 1);
define("ERRO_MAC_SEM_REGISTRO", 2);
define("ERRO_JSON_INVALIDO", 3);
define("ERRO_VALIDACAO", 4);
define("ERRO_EMPRESA_SEM_DADOS", 5);

define("SYNC_NAO_BAIXAR_SIG", 0);
define("SYNC_BAIXAR_SIG", 1);

define("NAO_REGISTRADO", 0);
define("REGISTRO_INSERIDO", 1);
define("PROVEDOR_PADRAO_RASTRO", "fused");

define("TIPO_CONTRIBUINTE_JURIDICA", 0);
define("TIPO_CONTRIBUINTE_FISICA", 1);

define("PENDENTE_SYNC_ERP", 1);

/**Api do google*/
define("KEY_API_MAPS_GOOGLE", "AIzaSyAzfKW8TqiEapi1-wW_y6145CoqdjRN07Y");

define("ORIGEM_PEDIDO_AFV", "P");

define("LOG_NOVO", 1);
define("LOG_ATUALIZACAO", 2);
define("LOG_DELETAR", 3);
define("LOG_LOGIN_VALIDO", 10);
define("LOG_LOGIN_INVALIDO", 11);
define("LOG_LOGIN_BLOQUEADO", 12);
define("LOG_PEDIDO_OK", 20);
define("LOG_PEDIDO_FALHA", 21);

define("STATUS_INATIVO", 0);
define("STATUS_ATIVO", 1);

/**Integrador */
define("URL_INTEGRADOR", env("FIREBIRD_API_URL"));
define("LOGIN_INTEG", 'Login');
define("VISITA_INTEG", 'visita/ObterVisita');
define("CONFIG_FILIAL_INTEG", 'empresa/ObterFilialConfiguracao');
define("CLIENTE_INTEG", 'cliente/ObterCliente');
define("CAMPANHA_INTEG", 'campanha/ObterCampanhaCabecalho');
define("CAMPANHA_BENEFICIO_INTEG", 'campanha/ObterCampanhaBeneficio');
define("CAMPANHA_MODALIDADE_INTEG", 'campanha/ObterCampanhaModalidade');
define("CAMPANHA_PARTICIPANTE_INTEG", 'campanha/ObterCampanhaParticipante');
define("CAMPANHA_REQUISITO_INTEG", 'campanha/ObterCampanhaRequisito');
define("AFV_CONFIGURACAO", 'afvconfiguracao/ObterAfvParametros');
define("CLIENTE_FORMA_DE_PAGAMENTO_INTEG", 'cliente/ObterClienteFormaPagamento');
define("CLIENTE_TABELA_GRUPO", 'cliente/ObterClienteTabelaGrupo');
define("AVISO_INTEG", 'aviso/ObterAviso');
define("CLIENTE_PRAZO_PAGAMENTO_INTEG", 'cliente/ObterClientePrazoPagamento');
define("FORNECEDOR_INTEG", 'fornecedor/ObterFornecedor');
define("FORMA_PRAZO_PGTO_INTEG", 'condicaopagamento/ObterFormaPrazoPagamento');
define("CLIENTE_TABELA_PRECO_INTEG", 'cliente/ObterClienteTabelaPreco');
define('INDICADOR_MARGEM_INTEG', 'vendaparametros/ObterIndicadorMargemVenda');
define('PRODUTO_INTEG', 'produto/ObterProduto');
define('PRODUTO_IMAGEM_INTEG', 'produto/ObterProdutoImagem');
define('PROTABELAITEN_INTEG', 'produto/ObterProdutoTabelaPreco');
define('META_DETALHE_INTEG', 'vendedor/ObterVendedorObjetivoDetail');
define('MIX_PRODUTO_INTEG', 'produto/ObterProdutoMix');
define('MOTIVO_INTEG', 'vendaparametros/ObterVendaMotivo');
define('PRAZO_PAGAMENTO_INTEG', 'condicaopagamento/ObterPrazoPagamento');
define('PRODUTO_DESCONTO_QUANTIDADE_INTEG', 'produto/ObterProdutoDescontoQuantidade');
define('PRODUTO_EMBALAGEM_INTEG', 'produto/ObterProdutoEmbalagem');
define('PRODUTO_ESTOQUE_INTEG', 'produto/ObterProdutoEstoque');
define('PRODUTO_GRUPO_INTEG', 'produto/ObterProdutoGrupo');
define('SUBGRUPO_INTEG', 'produto/ObterProdutoSubGrupo');
define('PRODUTO_IPI_INTEG', 'produto/ObterProdutoClassificacaoFiscal');
define('PRODUTO_ST_INTEG', 'produto/ObterProdutoTributacao');
define('PRODUTO_TABELA_PRECO_INTEG', 'condicaopagamento/ObterTabelaPreco');
define('FILIAL_INTEG', 'empresa/ObterEmpresa');
define('ENDERECO_INTEG', 'cliente/ObterClienteEndereco');
define('FORMA_PAGAMENTO_INTEG', 'condicaopagamento/ObterModoCobranca');
define('CONTATO_INTEG', 'cliente/ObterClienteContato');
define('VENDEDOR_INTEG', 'vendedor/ObterVendedor');
define('VENDEDOR_PRODUTO_INTEG', 'vendedor/ObterVendedorProduto');
define('VENDEDOR_TABELA_PRECO', 'vendedor/ObterVendedorTabelaPreco');
define('VENDEDOR_CLIENTE_INTEG', 'cliente/ObterClienteVendedor');
define('VENDA_PLANO_INTEG', 'vendaparametros/ObterVendaPlano');
define('ENVIAR_PEDIDO_INTEG', 'pedido/InserirPedidoErp');
define('TIPO_PEDIDO_INTEG', 'pedido/ObterTipoPedido');
define('STATUS_CLIENTE_INTEG', 'cliente/ObterClienteStatus');
define('STATUS_PEDIDO_INTEG', 'pedido/ObterStatusPedido');
define('STATUS_PRODUTO_INTEG', 'produto/ObterProdutoStatus');
define('NOTA_FISCAL_ITEM_INTEG', 'notafiscal/ObterNotaFiscalItem');
define('NOTA_FISCAL_INTEG', 'notafiscal/ObterNotaFiscalCabecalho');
define('ROTA_INTEG', 'rota/ObterRota');
define('META_INTEG', 'vendedor/ObterVendedorObjetivoHead');
define('VENDA_PLANO_PRODUTO_INTEG', 'produto/ObterProdutoVendaPlano');
define('TITULO_FINANCEIRO_INTEG', 'cliente/ObterClienteTitulo');
