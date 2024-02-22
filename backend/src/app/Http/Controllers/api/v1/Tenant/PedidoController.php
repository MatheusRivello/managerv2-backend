<?php

namespace App\Http\Controllers\api\v1\Tenant;

date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

use App\Http\Controllers\Controller;
use App\Models\Tenant\Pedido;
use App\Models\Tenant\PedidoItem;
use App\Services\BaseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/pedido/andamento",
     *     summary="Lista os pedidos em andamento",
     *     description="Lista todos os pedidos em andamentos.",
     *     operationId="Lista de pedidos em andamento",
     *     tags={"Pedidos"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de pedidos em andamentos",
     *         @OA\JsonContent( 
     *             @OA\Property(property="pedidos", type="object",
     *                          @OA\Property(property="data" , type="array", @OA\Items(ref="App\Models\Tenant\Pedido"))
     *             ),
     *             @OA\Property(property="qtdPedidos", type="integer"),
     *             @OA\Property(property="totalSincronizado", type="integer"),
     *             @OA\Property(property="totalPendente", type="integer"),
     *             @OA\Property(property="totalVendas", type="string"),     
     *         )
     *      ), 
     *     @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *     )
     *      
     * )
     **/
    public function listaAndamentoPedidos(Request $request)
    {
        try {
            $totBonificadas = 0;

            $this->service->verificarCamposRequest($request, RULE_PEDIDO_TENANT);

            $pedidos = Pedido::select(
                DB::raw("pedido.id AS idPedidoNuvem"),
                DB::raw("IF(filial.emp_raz IS NULL OR filial.emp_raz = '', filial.emp_fan, filial.emp_raz) AS filial"),
                DB::raw("CONCAT(vendedor.id,\"-\",vendedor.nome) AS vendedor"),
                DB::raw("pedido.id_pedido_dispositivo AS codAfv"),
                DB::raw("cliente.id_retaguarda AS codCliente"),
                DB::raw("pedido.id_retaguarda AS numPedido"),
                DB::raw("pedido.valorTotalComImpostos"),
                DB::raw("tipo_pedido.descricao AS tipoPedido"),
                DB::raw("DATE_FORMAT(pedido.dt_emissao,'%d-%m-%Y %H:%i') AS dataEmissao"),
                DB::raw("DATE_FORMAT(pedido.dt_cadastro,'%d-%m-%Y %H:%i') AS dataRecebimento"),
                DB::raw("DATE_FORMAT(pedido.dt_sinc_erp,'%d-%m-%Y %H:%i') AS dataSincronismo"),
                DB::raw("IF(pedido.status=0,IF(pedido.id_retaguarda IS NULL,'Aguardando sincronismo','Sincronizado'),
                CASE(pedido.status) 
                WHEN '0' THEN 'Aguardando sincronismo'
                WHEN '1' THEN 'Sincronizado'
                WHEN '5' THEN 'Pendente Aprovação'
                WHEN '7' THEN 'Pendente Reprovados'
                WHEN '10' THEN 'Aguardando Pagamento (PGS)'
                WHEN '11' THEN 'Pagamento Confirmado (PGS)'
                WHEN '13' THEN 'Pagamento Negado (PGS)' END) AS status"),
                DB::raw("IF(pedido.id_retaguarda IS NULL,0,1) AS statusSincronizado"),
                DB::raw("pedido.origem AS origem")
            )
                ->join("filial", "pedido.id_filial", "=", "filial.id")
                ->join("cliente", "pedido.id_cliente", "=", "cliente.id")
                ->join("tipo_pedido", "pedido.id_tipo_pedido", "=", "tipo_pedido.id")
                ->join("vendedor", "pedido.id_vendedor", "=", "vendedor.id")
                ->orderby("pedido.dt_cadastro", "DESC")
                ->where(function ($filtro) use ($request) {
                    if (!is_null($request->filial) && !empty($request->filial)) $filtro->whereIn("pedido.id_filial", $request->filial);
                    if (!is_null($request->vendedor) && !empty($request->vendedor)) $filtro->whereIn("pedido.id_vendedor", $request->vendedor);
                    if (!is_null($request->supervisor) && !empty($request->supervisor)) $filtro->whereIn("vendedor.supervisor", $request->supervisor);
                    if (!is_null($request->status) && !empty($request->status)) $filtro->whereIn("pedido.status", $request->status);
                    if (!is_null($request->tipoPedido) && !empty($request->tipoPedido)) $filtro->whereIn("pedido.id_tipo_pedido", $request->tipoPedido);
                    if (!is_null($request->origem) && !empty($request->origem)) $filtro->whereIn("pedido.origem", $request->origem);
                })
                ->whereRaw("pedido.dt_emissao BETWEEN '$request->dtInicio' AND '$request->dtFim'");


            $dados["pedidos"] = $pedidos->paginate(25);
            $dados["qtdPedidos"] = $pedidos->count();
            $dados["totalSincronizado"] = $pedidos->where('pedido.status', 0)->count();
            $dados["totalPendente"] = $dados["qtdPedidos"] - $dados["totalSincronizado"];
            $dados["totalVendas"] = $pedidos->sum('valor_total');


            return $this->service->verificarErro($dados);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function detalhePedidoPendente($idPedido)
    {
        try {
            $cabecalho["cabecalho"] = Pedido::select(
                "pedido.id",
                "pedido.id_retaguarda",
                "pedido.id_pedido_dispositivo",
                DB::raw("pedido.valorTotalComImpostos as valor_total"),
                DB::raw("pedido.valor_st as valor_st"),
                DB::raw("pedido.valor_frete as valor_frete"),
                DB::raw("pedido.valor_seguro as valor_seguro"),
                DB::raw("pedido.valor_ipi as valor_ipi"),
                DB::raw("DATE_FORMAT(pedido.dt_inicial,'%d-%m-%Y %T') as dt_inicial"),
                DB::raw("DATE_FORMAT(pedido.dt_emissao,'%d-%m-%Y %T') as dt_emissao"),
                DB::raw("DATE_FORMAT(pedido.dt_sinc_erp,'%d-%m-%Y %T') as dt_sinc_erp"),
                "pedido.qtde_itens",
                "pedido.ip",
                "pedido.valor_desconto",
                "pedido.observacao",
                DB::raw("DATE_FORMAT( pedido.previsao_entrega,'%d-%m-%Y') as previsao_entrega"),
                DB::raw("protabela_preco.tab_desc as tabela_preco"),
                DB::raw("tipo_pedido.descricao as tipo_pedido"),
                DB::raw("prazo_pagamento.descricao as prazo_pgto"),
                DB::raw("forma_pagamento.descricao as forma_pgto"),
                "filial.emp_raz",
                "filial.emp_fan",
                DB::raw("pedido.valorVerba as valor_verba"),
                DB::raw("vendedor.nome as nome_vendedor"),
                DB::raw("vendedor.id as id_vendedor"),
                DB::raw("cliente.razao_social as cliente_razao"),
                DB::raw("cliente.nome_fantasia as cliente_fantasia"),
                DB::raw("IF(pedido.status=0,IF(pedido.id_retaguarda IS NULL,'Aguardando sincronismo','Sincronizado'),
                CASE(pedido.status) 
                WHEN '0' THEN 'Aguardando sincronismo'
                WHEN '1' THEN 'Sincronizado'
                WHEN '5' THEN 'Pendente Aprovação'
                WHEN '7' THEN 'Pendente Reprovados'
                WHEN '10' THEN 'Aguardando Pagamento (PGS)'
                WHEN '11' THEN 'Pagamento Confirmado (PGS)'
                WHEN '13' THEN 'Pagamento Negado (PGS)' END) AS status"),
                "pedido.origem"
            )
                ->join("protabela_preco", "pedido.id_tabela", "=", "protabela_preco.id")
                ->join("prazo_pagamento", "pedido.id_prazo_pgto", "=", "prazo_pagamento.id")
                ->join("forma_pagamento", "pedido.id_forma_pgto", "=", "forma_pagamento.id", "left")
                ->join("filial", "pedido.id_filial", "=", "filial.id")
                ->join("cliente", "pedido.id_cliente", "=", "cliente.id")
                ->join("tipo_pedido", "pedido.id_tipo_pedido", "=", "tipo_pedido.id", "left")
                ->join("vendedor", "pedido.id_vendedor", "=", "vendedor.id")
                ->where("pedido.id", $idPedido)
                ->get();

            if ($cabecalho != NULL) {

                $item =  PedidoItem::select(
                    DB::raw("CONCAT(produto.id_retaguarda,'/',LOWER(produto.descricao)) AS descricao_produto"),
                    "pedido_item.numero_item",
                    "pedido_item.unidvenda",
                    "pedido_item.quantidade",
                    "pedido_item.id_produto",
                    "produto_imagem.url",
                    DB::raw("pedido_item.valorVerba * pedido_item.quantidade as valor_verba"),
                    DB::raw("pedido_item.valor_st as valor_st"),
                    DB::raw("pedido_item.valor_ipi as valor_ipi"),
                    DB::raw("pedido_item.valor_frete as valor_frete"),
                    DB::raw("pedido_item.valor_seguro as valor_seguro"),
                    DB::raw("pedido_item.percentualdesconto as percentualdesconto"),
                    DB::raw("pedido_item.valor_unitario as valor_unitario"),
                    DB::raw("pedido_item.valorTotalComImpostos as valor_total"),
                    "pedido_item.status"
                )
                    ->join("produto", "pedido_item.id_produto", "=", "produto.id")
                    ->join("produto_imagem", "pedido_item.id_produto", "=", "produto_imagem.id", "left")
                    ->where("pedido_item.id_pedido", $idPedido);

                $cabecalho["itens"] = $item->get();
            }

            return $this->service->verificarErro($cabecalho);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function totalPedidosCoordenadas(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_PEDIDO_TENANT);

            $pedidos = Pedido::select(
                DB::raw("IF(filial.emp_raz IS NULL OR filial.emp_raz = '', filial.emp_fan, filial.emp_raz) AS nomeEmpresa"),
                DB::raw("vendedor.id as idVendedor"),
                DB::raw("vendedor.nome as nomeVendedor"),
                DB::raw("count(pedido.id) AS totalDePedidos"),
                DB::raw("COUNT(DISTINCT pedido.id_cliente) AS totalPositivacao"),
                DB::raw("SUM(pedido.valorTotalComImpostos) AS valorTotalGeral"),
                DB::raw("SUM(pedido.valorTotalComImpostos)/COUNT(pedido.id) AS ticketMedioTotal"),
                DB::raw("SUM(IF(pedido.latitude IS null,1,0)) AS totalPedidoSEM"),
                DB::raw("SUM(IF(pedido.latitude IS not null,1,0)) AS totalPedidoCOM")
            )
                ->join("filial", "pedido.id_filial", "=", "filial.id")
                ->join("cliente", "pedido.id_cliente", "=", "cliente.id")
                ->join("tipo_pedido", "pedido.id_tipo_pedido", "=", "tipo_pedido.id")
                ->join("vendedor", "pedido.id_vendedor", "=", "vendedor.id")
                ->orderby("vendedor.nome", "DESC")
                ->groupBy('filial.emp_fan', 'filial.emp_raz', 'vendedor.id', 'vendedor.nome')
                ->where(function ($filtro) use ($request) {
                    if (!is_null($request->filial) && !empty($request->filial)) $filtro->whereIn("pedido.id_filial", $request->filial);
                    if (!is_null($request->vendedor) && !empty($request->vendedor)) $filtro->whereIn("pedido.id_vendedor", $request->vendedor);
                    if (!is_null($request->supervisor) && !empty($request->supervisor)) $filtro->whereIn("vendedor.supervisor", $request->supervisor);
                    if (!is_null($request->status) && !empty($request->status)) $filtro->whereIn("pedido.status", $request->status);
                    if (!is_null($request->tipoPedido) && !empty($request->tipoPedido)) $filtro->whereIn("pedido.id_tipo_pedido", $request->tipoPedido);
                    if (!is_null($request->origem) && !empty($request->origem)) $filtro->whereIn("pedido.origem", $request->origem);
                })
                ->whereRaw("pedido.dt_emissao BETWEEN '$request->dtInicio' AND '$request->dtFim'");

            $qtdPedidos = $pedidos->get()->reduce(fn ($valor, $e) => $valor + $e["totalDePedidos"]);
            $totalVendas = $pedidos->get()->reduce(fn ($valor, $e) => $valor + $e["valorTotalGeral"]);

            $dados["qtdPedidos"] = $qtdPedidos;
            $dados["totalSincronizado"] = $pedidos->where('pedido.status', 0)->count();
            $dados["totalPendente"] = $dados["qtdPedidos"] - $dados["totalSincronizado"];
            $dados["totalVendas"] = round($totalVendas, 2);
            $dados["pedidos"] = $pedidos->paginate(25);

            return $this->service->verificarErro($dados);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/tenant/pedido/coordenada/total",
     *     summary="Lista os pedidos coordenados pelos filtros.",
     *     description="Lista os pedidos coordenados pelos filtros.",
     *     operationId="Lista os pedidos coordenados pelos filtros.",
     *     tags={"Pedidos"},
     *     @OA\Parameter(
     *         name="dtInicio",
     *         in="query",
     *         description="Data inicial dos pedidos.",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-01-01", description="Data inicial dos pedidos")
     *     ),
     *      @OA\Parameter(
     *         name="dtFim",
     *         in="query",
     *         description="Data final dos pedidos.",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-01-01", description="Data final dos pedidos")
     *     ),
     *     @OA\Parameter(
     *         name="filial",
     *         in="query",
     *         description="Filial dos pedidos.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID da filial")
     *     ),
     *     @OA\Parameter(
     *         name="vendedor",
     *         in="query",
     *         description="Vendedor no quais pertencem os pedidos",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="Vendedor responsável pelos pedidos")
     *     ),
     *     @OA\Parameter(
     *         name="supervisor",
     *         in="query",
     *         description="filtro pelo supervisor do vendedor",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="filtro pelo supervisor do vendedor")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de pedidos coordenados",
     *         @OA\JsonContent( 
     *             @OA\Property(property="qtdPedidos", type="integer"),
     *             @OA\Property(property="totalSincronizado", type="integer"),
     *             @OA\Property(property="totalPendente", type="integer"),
     *             @OA\Property(property="totalVendas", type="number"),
     *             @OA\Property(property="pedidos", type="object",
     *                          @OA\Property(property="current_page" , type="integer"), 
     *                          @OA\Property(property="data" , type="array", 
     *                             @OA\Items(
     *                                 @OA\Property(property="nomeEmpresa", type="string"),
     *                                 @OA\Property(property="idVendedor", type="integer"),
     *                                 @OA\Property(property="nomeVendedor", type="string"),
     *                                 @OA\Property(property="totalDePedidos", type="integer"),
     *                                 @OA\Property(property="totalPositivacao", type="integer"),
     *                                 @OA\Property(property="valorTotalGeral", type="integer"),
     *                                 @OA\Property(property="ticketMedioTotal", type="integer"),
     *                                 @OA\Property(property="totalPedidoSEM", type="integer"),
     *                                 @OA\Property(property="totalPedidoCOM", type="integer"),
     *                             )
     *                           )
     *             ),     
     *         )
     *      ), 
     *     @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *     )
     *      
     * )
     **/
    public function listarPedidosVendedor(Request $request, $id)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_PEDIDO_TENANT);

            $pedidos = Pedido::select(
                "pedido.id_filial",
                DB::raw("IF(filial.emp_raz IS NULL OR filial.emp_raz = '', filial.emp_fan, filial.emp_raz) AS nome_empresa"),
                DB::raw("pedido.id as idNuvem"),
                DB::raw("pedido.id_retaguarda as idERP"),
                DB::raw("pedido.id_pedido_dispositivo as idPedidoAFV"),
                DB::raw("IF(pedido.latitude IS NOT NULL AND pedido.longitude IS NOT NULL,'true','false') AS coordenadas"),
                DB::raw("pedido.valorTotalComImpostos AS valor_total"),
                DB::raw("DATE_FORMAT(pedido.dt_emissao,'%d-%m-%Y %T') AS dataEmissao"),
                DB::raw("IF(cliente.razao_social = '' OR cliente.razao_social IS NULL"),
                DB::raw("IF( CHARACTER_LENGTH(cliente.nome_fantasia) > 20, CONCAT(SUBSTRING(cliente.nome_fantasia, 1, 20), '...'),cliente.nome_fantasia),
                        IF(CHARACTER_LENGTH(cliente.razao_social) > 20,
                            CONCAT(SUBSTRING(cliente.razao_social, 1, 20), '...'
                          ),cliente.razao_social
                  )
              ) AS nomeCliente")
            )
                ->join("filial", "pedido.id_filial", "=", "filial.id")
                ->join("cliente", "pedido.id_cliente", "=", "cliente.id")
                ->orderby("pedido.id", "DESC")
                ->groupBy(
                    'pedido.id_filial',
                    'pedido.id',
                    'pedido.id_retaguarda',
                    'filial.emp_raz',
                    'filial.emp_fan',
                    'pedido.id_pedido_dispositivo',
                    'pedido.latitude',
                    'pedido.longitude',
                    'pedido.valorTotalComImpostos',
                    'pedido.dt_emissao',
                    'cliente.razao_social',
                    'cliente.nome_fantasia',
                )
                ->where(function ($filtro) use ($request) {
                    if (!is_null($request->filial) && !empty($request->filial)) $filtro->whereIn("pedido.id_filial", $request->filial);
                    if (!is_null($request->status) && !empty($request->status)) $filtro->whereIn("pedido.status", $request->status);
                    if (!is_null($request->tipoPedido) && !empty($request->tipoPedido)) $filtro->whereIn("pedido.id_tipo_pedido", $request->tipoPedido);
                    if (!is_null($request->origem) && !empty($request->origem)) $filtro->whereIn("pedido.origem", $request->origem);
                })
                ->whereRaw("pedido.dt_emissao BETWEEN '$request->dtInicio' AND '$request->dtFim'")
                ->where('pedido.id_vendedor', $id);


            return $this->service->verificarErro($pedidos->paginate(50));
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tenant/pedido/coordenada/lista",
     *     summary="Traz os pedidos coordenados pelos filtros nom formato de lista.",
     *     description="Traz os pedidos coordenados pelos filtros no formato de lista.",
     *     operationId="Traz os pedidos coordenados pelos filtros no formato de lista.",
     *     tags={"Pedidos"},
     *     @OA\Parameter(
     *         name="dtInicio",
     *         in="query",
     *         description="Data inicial dos pedidos.",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-01-01", description="Data inicial dos pedidos")
     *     ),
     *     @OA\Parameter(
     *         name="dtFim",
     *         in="query",
     *         description="Data final dos pedidos.",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-01-01", description="Data final dos pedidos")
     *     ),
     *     @OA\Parameter(
     *         name="filial",
     *         in="query",
     *         description="Filial dos pedidos.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID da filial")
     *     ),
     *     @OA\Parameter(
     *         name="vendedor",
     *         in="query",
     *         description="Vendedor no quais pertencem os pedidos",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="Vendedor responsável pelos pedidos")
     *     ),
     *     @OA\Parameter(
     *         name="supervisor",
     *         in="query",
     *         description="filtro pelo supervisor do vendedor",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="filtro pelo supervisor do vendedor")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de pedidos coordenados",
     *         @OA\JsonContent( 
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="idVendedor", type="integer"),
     *                     @OA\Property(property="nomeVendedor", type="string"),
     *                     @OA\Property(property="razaoSocial", type="string"),
     *                     @OA\Property(property="nomeFantasia", type="string"),
     *                     @OA\Property(property="cnpj", type="string"),
     *                     @OA\Property(property="tipoPedido", type="string"),
     *                     @OA\Property(property="telefone", type="string"),
     *                     @OA\Property(property="idPedido", type="integer"),
     *                     @OA\Property(property="idDispositivo", type="integer"),
     *                     @OA\Property(property="notaFiscal", type="string"),
     *                     @OA\Property(property="valorTotalComImpostos", type="number"),
     *                     @OA\Property(property="qtdItens", type="integer"),
     *                     @OA\Property(property="latitude", type="string"),
     *                     @OA\Property(property="longitude", type="string"),
     *                     @OA\Property(property="precisao", type="string"),
     *                     @OA\Property(property="formaPagamento", type="string"),
     *                     @OA\Property(property="dtEmissao", type="string"),
     *                     @OA\Property(property="cidade", type="string"),
     *                     @OA\Property(property="logradouro", type="string"),
     *                     @OA\Property(property="cep", type="string"),
     *                     @OA\Property(property="numero", type="string"),
     *                     @OA\Property(property="bairro", type="string"),
     *                     @OA\Property(property="uf", type="string"),
     *                     @OA\Property(property="distancia", type="number"),
     *                 ),
     *             ),
     *         ),
     *     ), 
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     * )
     **/
    public function listaPedidosComCoordenadas(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_PEDIDO_TENANT);

            $pedidos = Pedido::select(
                DB::raw("vendedor.id AS idVendedor"),
                DB::raw("vendedor.nome AS nomeVendedor"),
                DB::raw("cliente.razao_social AS razaoSocial"),
                DB::raw("cliente.nome_fantasia AS nomeFantasia"),
                DB::raw("cliente.cnpj"),
                DB::raw("tipo_pedido.descricao AS tipoPedido"),
                DB::raw("cliente.telefone"),
                DB::raw("pedido.id AS idPedido"),
                DB::raw("pedido.id_pedido_dispositivo AS idDispositivo"),
                DB::raw("pedido.nota_fiscal AS notaFiscal"),
                DB::raw("pedido.ValorTotalComImpostos AS valorTotalComImpostos"),
                DB::raw("pedido.qtde_itens AS qtdItens"),
                DB::raw("pedido.latitude"),
                DB::raw("pedido.longitude"),
                DB::raw("pedido.precisao"),
                DB::raw("forma_pagamento.descricao AS formaPagamento"),
                DB::raw("DATE_FORMAT(pedido.dt_emissao,'%d-%m-%Y %H:%ih') AS dtEmissao"),
                DB::raw("cidade.descricao AS cidade"),
                DB::raw("endereco.logradouro"),
                DB::raw("endereco.cep"),
                DB::raw("endereco.numero"),
                DB::raw("endereco.bairro"),
                DB::raw("endereco.uf"),
                DB::raw("((6371 * ACOS(COS(PI() * (90 - pedido.latitude) / 180) * COS((90 - endereco.latitude) * PI() / 180) + SIN((90 - pedido.latitude) * PI() / 180) * SIN((90 - endereco.latitude) * PI() / 180) * COS((endereco.longitude - endereco.longitude) * PI() / 180))) * 1000) AS distancia")
            )
                ->join("endereco", "pedido.id_cliente", "=", "endereco.id_cliente", "left")
                ->join("cidade", "endereco.id_cidade", "=", "cidade.id", "left")
                ->join("forma_pagamento", "pedido.id_forma_pgto", "=", "forma_pagamento.id")
                ->join("filial", "pedido.id_filial", "=", "filial.id")
                ->join("cliente", "pedido.id_cliente", "=", "cliente.id")
                ->join("tipo_pedido", "pedido.id_tipo_pedido", "=", "tipo_pedido.id")
                ->join("vendedor", "pedido.id_vendedor", "=", "vendedor.id")
                ->where(function ($filtro) use ($request) {
                    if (!is_null($request->filial) && !empty($request->filial)) $filtro->whereIn("pedido.id_filial", $request->filial);
                    if (!is_null($request->vendedor) && !empty($request->vendedor)) $filtro->whereIn("pedido.id_vendedor", $request->vendedor);
                    if (!is_null($request->supervisor) && !empty($request->supervisor)) $filtro->whereIn("vendedor.supervisor", $request->supervisor);
                    if (!is_null($request->tipoPedido) && !empty($request->tipoPedido)) $filtro->whereIn("pedido.id_tipo_pedido", $request->tipoPedido);
                })
                ->where("endereco.tit_cod", 1)
                ->whereRaw("endereco.latitude is not null")
                ->orderby("pedido.dt_cadastro", "DESC");

            //SE NAO FOR INFORMADO O ID DO PEDIDO ELE BUSCA POR PERIODO
            if ($request->pedido == NULL) {
                if (is_null($request->dtFim)) { //Se nao infomar a data final ele faz uma consulta apenas com a data início
                    $pedidos->like("pedido.dt_emissao", $request->dtInicio);
                } else {
                    $pedidos->whereRaw("pedido.dt_emissao BETWEEN '$request->dtInicio' AND '$request->dtFim'");
                }
            } else {
                $pedidos->where("pedido.id", $request->pedido);
            }

            return $this->service->verificarErro($pedidos->paginate(50));
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function pedidosPorEstadosCidades(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_PEDIDO_TENANT);

            $conteudo = [];

            if ($request->tipo == "estado") {
                $estados_A = $this->estadosECidadesMaisAtendidas('estados', $request);
                $estados_B = $this->estadosECidadesMaisAtendidas('estados', $request, TRUE);
                $conteudo = $this->_somaRegistrosEstadosOuCidades($estados_A, $estados_B);
            } else if ($request->tipo == "cidade") {
                $cidades_A = $this->estadosECidadesMaisAtendidas('cidades', $request);
                $cidades_B = $this->estadosECidadesMaisAtendidas('cidades', $request, TRUE);
                $conteudo = $this->_somaRegistrosEstadosOuCidades($cidades_A, $cidades_B);
            }

            return $this->service->verificarErro($conteudo);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    private function estadosECidadesMaisAtendidas($tipo, $request, $enderecoTabelaPedido = FALSE)
    {
        try {
            $dados = Pedido::whereRaw("pedido.dt_emissao BETWEEN '$request->dtInicio' AND '$request->dtFim'");

            //Verifica qual o tipo de consulta será feito
            if ($tipo == "estados") {
                $dados->select(
                    DB::raw("IF(uf.id IS NULL, 'Vazio',uf.id) AS id"),
                    "uf.descricao",
                    DB::raw("IF(uf.id IS NOT NULL, COUNT(uf.id),COUNT(1)) AS qtd"),
                    DB::raw("IF(uf.id IS NULL, 'Vazio',uf.id) AS uf"),
                    DB::raw("IF(regiao.descricao IS NULL, 'Vazio',regiao.descricao) AS regiao"),
                    DB::raw("SUM(pedido.valorTotalComImpostos) AS valor")
                );

                $dados->groupBy(
                    "uf.id",
                    "uf.descricao",
                    "regiao.descricao"
                );
            } else if ($tipo == "cidades") {
                $dados->select(
                    DB::raw("IF(cidade.id IS NULL, 'Vazio',cidade.id) AS id"),
                    DB::raw("IF(cidade.descricao IS NULL,'Clientes sem endereços',cidade.descricao) AS descricao"),
                    DB::raw("IF(cidade.id IS NOT NULL, COUNT(cidade.id),COUNT(1)) AS qtd"),
                    DB::raw("SUM(pedido.valorTotalComImpostos) AS valor"),
                    DB::raw("IF(cidade.uf IS NULL, 'Vazio',cidade.uf) AS uf"),
                    DB::raw("IF(regiao.descricao IS NULL, 'Vazio',regiao.descricao) AS regiao")
                );
                $dados->groupBy(
                    "cidade.id",
                    "cidade.descricao",
                    "cidade.uf",
                    "regiao.descricao",
                );
            }

            //Se esta opçao for FALSE ele fara a consulta do endereço diretamente pela tabela de endereco contida no cliente
            if ($enderecoTabelaPedido == FALSE) {
                $dados->join("endereco", "pedido.id_cliente", "=", "endereco.id_cliente", "LEFT");
                $dados->where("endereco.tit_cod", 1);
            } else { //Senao ele fará a consulta diretamente pela tabela de pedido com o compo (id_endereco)
                $dados->join("endereco", "pedido.id_endereco", "=", "endereco.id_retaguarda");
            }

            $dados->join("cidade", "endereco.id_cidade", "=", "cidade.id", "LEFT")
                ->join("uf", "cidade.uf", "=", "uf.id", "LEFT")
                ->join("regiao", "uf.id_regiao", "=", "regiao.id", "LEFT");

            $dados->where(function ($filtro) use ($request) {
                if (!is_null($request->filial) && !empty($request->filial)) $filtro->whereIn("pedido.id_filial", $request->filial);
                if (!is_null($request->vendedor) && !empty($request->vendedor)) $filtro->whereIn("pedido.id_vendedor", $request->vendedor);
                if (!is_null($request->supervisor) && !empty($request->supervisor)) $filtro->whereIn("vendedor.supervisor", $request->supervisor);
                if (!is_null($request->tipoPedido) && !empty($request->tipoPedido)) $filtro->whereIn("pedido.id_tipo_pedido", $request->tipoPedido);
            });

            return $this->service->verificarErro($dados->get());
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tenant/coordenada/regioes",
     *     summary="Lista os pedidos baseado nas regiões",
     *     description="Lista os pedidos baseado nas regiões",
     *     operationId="Lista os pedidos baseado nas regiões",
     *     tags={"Pedidos"},
     *      @OA\Parameter(
     *         name="dtInicio",
     *         in="query",
     *         description="Data inicial dos pedidos.",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-01-01", description="Data inicial dos pedidos")
     *     ),
     *      @OA\Parameter(
     *         name="dtFim",
     *         in="query",
     *         description="Data final dos pedidos.",
     *         required=false,
     *         @OA\Schema(type="string", example="2023-01-01", description="Data final dos pedidos")
     *     ),
     *     @OA\Parameter(
     *         name="filial",
     *         in="query",
     *         description="Filial dos pedidos.",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="ID da filial")
     *     ),
     *     @OA\Parameter(
     *         name="vendedor",
     *         in="query",
     *         description="Vendedor no quais pertencem os pedidos",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="Vendedor responsável pelos pedidos")
     *     ),
     *     @OA\Parameter(
     *         name="supervisor",
     *         in="query",
     *         description="filtro pelo supervisor do vendedor",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="filtro pelo supervisor do vendedor")
     *     ),
     *     @OA\Parameter(
     *         name="tipoPedido",
     *         in="query",
     *         description="filtro pelo tipo de pedido",
     *         required=false,
     *         @OA\Schema(type="integer", example="1", description="filtro pelo supervisor do vendedor")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de pedidos baseado nas regiões.",
     *         @OA\JsonContent( 
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="id",type="integer"),
     *                          @OA\Property(property="latitude" ,type="string"),
     *                          @OA\Property(property="longitude",type="string"),
     *                          @OA\Property(property="precisao",type="string"),
     *                          @OA\Property(property="valorTotalComImpostos",type="number"),
     *                      ),
     *             ),        
     *         )
     *      ), 
     *     @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *     )
     *      
     * )
     **/
    public function somenteCoordenadas(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_PEDIDO_TENANT);

            $dados = Pedido::select(
                "id",
                "latitude",
                "longitude",
                "precisao",
                "valorTotalComImpostos"
            )
                ->where(function ($filtro) use ($request) {
                    if (!is_null($request->filial) && !empty($request->filial)) $filtro->whereIn("pedido.id_filial", $request->filial);
                    if (!is_null($request->vendedor) && !empty($request->vendedor)) $filtro->whereIn("pedido.id_vendedor", $request->vendedor);
                    if (!is_null($request->supervisor) && !empty($request->supervisor)) $filtro->whereIn("vendedor.supervisor", $request->supervisor);
                    if (!is_null($request->tipoPedido) && !empty($request->tipoPedido)) $filtro->whereIn("pedido.id_tipo_pedido", $request->tipoPedido);
                })
                ->whereRaw("latitude IS NOT NULL")
                ->whereRaw("longitude IS NOT NULL")
                ->whereRaw("pedido.dt_emissao BETWEEN '$request->dtInicio' AND '$request->dtFim'");

            return $this->service->verificarErro($dados->paginate(50));
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    private function _somaRegistrosEstadosOuCidades($array_A, $array_B)
    {
        $vetorRetorno = [];
        // dd($array_A["message"] == "Registro não encontrado" && $array_B["message"] == "Registro não encontrado");
        if (
            isset($array_A["message"]) && isset($array_B["message"]) &&
            $array_A["message"] == "Registro não encontrado" && $array_B["message"] == "Registro não encontrado"
        ) {
            return ["message" => "Registro não encontrado"];
        }

        $tamanhoArray_A = count($array_A);
        $tamanhoArray_B = count($array_B);

        if ($tamanhoArray_A > 0 && $tamanhoArray_B == 0) { //Se tiver dados no array_A mais nao no array_B ele atribuirá somente os dados do array_A
            $vetorRetorno = $array_A;
        } else if ($tamanhoArray_A == 0 && $tamanhoArray_B > 0) { //Senao tiver dados no array_A e tiver no array_B ele atribuirá somente os dados do array_B
            $vetorRetorno = $array_B;
        } else if ($tamanhoArray_A > 0 && $tamanhoArray_B > 0) { //Se ambos tiverem dados ele concatena os dados e forma um novo array

            //Atribuindo valores padrões para variavel que será utilizada no foreach
            $array_PRIMARIO = $array_B;
            $array_SECUNDARIO = $array_A;

            //Caso o array_A for maior ou igual a array_B ele atribui seus dados a arrayForeach
            if ($tamanhoArray_A >= $tamanhoArray_B) {
                $array_PRIMARIO = $array_A;
                $array_SECUNDARIO = $array_B;
            }

            foreach ($array_PRIMARIO as $item) {
                $posicaoEm_SECUNDARIO = array_search($item["id"], array_column($array_SECUNDARIO, 'id')); // Procura o ID que está no array_B no array_A
                $qtd = 0;
                $valor = 0;

                //Se encontrou o valor do array_A em array_B
                if ($posicaoEm_SECUNDARIO !== FALSE) {
                    $qtd_SECUNDARIO = floatval($array_SECUNDARIO[$posicaoEm_SECUNDARIO]["qtd"]);
                    $valor_SECUNDARIO = floatval($array_SECUNDARIO[$posicaoEm_SECUNDARIO]["valor"]);

                    $qtd_item = floatval($item["qtd"]);
                    $valor_item = floatval($item["valor"]);

                    $qtd = $qtd_SECUNDARIO + $qtd_item;
                    $valor = $valor_SECUNDARIO + $valor_item;
                } else {
                    $qtd = floatval($item["qtd"]);
                    $valor = floatval($item["valor"]);
                }

                //Atribui os dados atualizados no vetor de retorno
                array_push($vetorRetorno, [
                    "id" => $item["id"],
                    "uf" => $item["uf"],
                    "descricao" => $item["descricao"],
                    "qtd" => $qtd,
                    "valor" => $valor,
                ]);
            }
        }

        return $vetorRetorno;
    }

    /**
     * @OA\Get(
     *     path="/api/tenant/pedido/por-dias",
     *     summary="Lista os pedidos,clientes e as receitas baseado nos dias da semana e no histórico de alguns dias passados.",
     *     description="Lista os pedidos baseado nos dias da semana.",
     *     operationId="Lista os pedidos baseado nos dias da semana.",
     *     tags={"Pedidos"},
     *     @OA\Response(
     *         response=200,
     *         description="Devolve um array de pedidos baseado com o histórico de alguns dias da semana",
     *         @OA\JsonContent( 
     *             @OA\Property(property="qtdClientes", type="object",
     *                          @OA\Property(property="hoje" , type="integer"),
     *                          @OA\Property(property="ontem" , type="integer"),
     *                          @OA\Property(property="haSeteDias" , type="integer")
     *             ),
     *             @OA\Property(property="qtdPedidos", type="object",
     *                          @OA\Property(property="hoje" , type="integer"),
     *                          @OA\Property(property="ontem" , type="integer"),
     *                          @OA\Property(property="haSeteDias" , type="integer"),
     *                          @OA\Property(property="ultimosSeteDias" , type="object",
     *                             @OA\Property(property="dia" , type="string"),
     *                             @OA\Property(property="qtd" , type="integer"),
     *                             
     *                          )
     *             ),
     *             @OA\Property(property="receita", type="object",
     *                          @OA\Property(property="hoje" , type="integer"),
     *                          @OA\Property(property="ontem" , type="integer"),
     *                          @OA\Property(property="haSeteDias" , type="integer"),
     *                          @OA\Property(property="ultimosSeteDias" , type="object",
     *                             @OA\Property(property="dia" , type="string"),
     *                             @OA\Property(property="valor" , type="number"),
     *                          )
     *             ),        
     *         )
     *      ), 
     *     @OA\Response(
     *       response=401,
     *       description="Não autorizado"
     *     )
     *      
     * )
     **/
    public function totaisPorDias()
    {
        $dataAtual = Carbon::now()->format('Y-m-d');
        $dataFormatada = Carbon::now();
        $dataDe7DiasAtras = Carbon::now()->sub('7days')->format('Y-m-d');
        $dataAtualMenosUmDias = Carbon::now()->sub('1days')->format('Y-m-d');
        $receitaDoDia = 0;
        $receitaDiaAnterior = 0;
        $qtdDataAtual = 0;
        $qtdDiaAnterior = 0;
        $qtd7Dias = 0;
        $receitaHaSeteDias = 0;
        $resultado = [];
        $resultado['qtdClientes'] = [];
        $resultado['qtdPedidos'] = [];
        $resultado['receita'] = [];

        $registroPedidos = Pedido::select('dt_emissao', 'valorTotalComImpostos', 'id_cliente')
            ->where('dt_emissao', '<=', Carbon::now())
            ->where('dt_emissao', '>=', $dataDe7DiasAtras)
            ->orderBy('dt_emissao', 'asc')
            ->get();

        $clientesNoDia = [];
        $clientesDiaAnterior = [];
        $clientes7Dias = [];
        $qtdClientesNoDia = 0;
        $qtdClientesDiaAnterior = 0;
        $qtdClientes7Dias = 0;

        foreach ($registroPedidos as $value) {

            $dataFormatada = $value['dt_emissao']->format('Y-m-d');

            if ($dataFormatada == $dataAtual) {
                $qtdDataAtual++;
                $receitaDoDia += $value['valorTotalComImpostos'];
                $clientesNoDia[] = $value->id_cliente;
            } else if ($dataFormatada == $dataAtualMenosUmDias) {
                $qtdDiaAnterior++;
                $receitaDiaAnterior += $value['valorTotalComImpostos'];
                $clientesDiaAnterior[] = $value->id_cliente;
            } else if ($dataFormatada == $dataDe7DiasAtras) {
                $qtd7Dias++;
                $receitaHaSeteDias += $value['valorTotalComImpostos'];
                $clientes7Dias[] = $value->id_cliente;
            }
            $qtdClientesNoDia = count(array_unique($clientesNoDia)) > 0 ? count(array_unique($clientesNoDia)) : 0;
            $qtdClientesDiaAnterior = count(array_unique($clientesDiaAnterior));
            $qtdClientes7Dias = count(array_unique($clientes7Dias));
        }

        $resultado['receita']['hoje'] = $receitaDoDia;
        $resultado['receita']['ontem'] = $receitaDiaAnterior;
        $resultado['receita']['haSeteDias'] = $receitaHaSeteDias;
        $resultado['receita']['ultimosSeteDias'] =  $this->somarDias($registroPedidos);

        $resultado['qtdPedidos']['hoje'] = $qtdDataAtual;
        $resultado['qtdPedidos']['ontem'] = $qtdDiaAnterior;
        $resultado['qtdPedidos']['haSeteDias'] = $qtd7Dias;
        $resultado['qtdPedidos']['ultimosSeteDias'] = $this->somarQuantidadeDePedidos($registroPedidos);

        $resultado['qtdClientes']['hoje'] = $qtdClientesNoDia;
        $resultado['qtdClientes']['ontem'] = $qtdClientesDiaAnterior;
        $resultado['qtdClientes']['haSeteDias'] = $qtdClientes7Dias;

        return $resultado;
    }

    public function somarDias($dados)
    {

        $valoresDaSegunda = 0;
        $valoresDaTerça = 0;
        $valoresDaQuarta = 0;
        $valoresDaQuinta = 0;
        $valoresDaSexta = 0;
        $valoresDoSabado = 0;
        $valoresDoDomingo = 0;

        foreach ($dados as $value) {

            switch ($value['dt_emissao']->format('l')) {
                case 'Monday':
                    $valoresDaSegunda += $value['valorTotalComImpostos'];
                    break;
                case 'Tuesday':
                    $valoresDaTerça += $value['valorTotalComImpostos'];
                    break;
                case 'Wednesday':
                    $valoresDaQuarta += $value['valorTotalComImpostos'];
                    break;
                case 'Thursday':
                    $valoresDaQuinta += $value['valorTotalComImpostos'];
                    break;
                case 'Friday':
                    $valoresDaSexta += $value['valorTotalComImpostos'];
                    break;
                case 'Saturday':
                    $valoresDoSabado += $value['valorTotalComImpostos'];
                    break;
                case 'Sunday':
                    $valoresDoDomingo += $value['valorTotalComImpostos'];
                    break;
            }
        }
        $totDaSemana = array(
            'Segunda' => $valoresDaSegunda,
            'Terça' => $valoresDaTerça,
            'Quarta' => $valoresDaQuarta,
            'Quinta' => $valoresDaQuinta,
            'Sexta' => $valoresDaSexta,
            'Sábado' => $valoresDoSabado,
            'Domingo' => $valoresDoDomingo,
        );
        return $this->map('receita', $totDaSemana);
    }

    public function somarQuantidadeDePedidos($dados)
    {

        $qtdDaSegunda = 0;
        $qtdDaTerça = 0;
        $qtdDaQuarta = 0;
        $qtdDaQuinta = 0;
        $qtdDaSexta = 0;
        $qtdDoSabado = 0;
        $qtdDoDomingo = 0;

        foreach ($dados as $value) {

            switch ($value['dt_emissao']->format('l')) {
                case 'Monday':
                    $qtdDaSegunda++;
                    break;
                case 'Tuesday':
                    $qtdDaTerça++;
                    break;
                case 'Wednesday':
                    $qtdDaQuarta++;
                    break;
                case 'Thursday':
                    $qtdDaQuinta++;
                    break;
                case 'Friday':
                    $qtdDaSexta++;
                    break;
                case 'Saturday':
                    $qtdDoSabado++;
                    break;
                case 'Sunday':
                    $qtdDoDomingo++;
                    break;
            }
        }
        $totDaSemana = array(
            'Segunda' => $qtdDaSegunda,
            'Terça' => $qtdDaTerça,
            'Quarta' => $qtdDaQuarta,
            'Quinta' => $qtdDaQuinta,
            'Sexta' => $qtdDaSexta,
            'Sábado' => $qtdDoSabado,
            'Domingo' => $qtdDoDomingo,
        );
        return $this->map('pedido', $totDaSemana);
    }

    public function map($tipo, $dados)
    {
        $arrayMapeado = array();
        if ($tipo == 'receita') {
            foreach ($dados as $key => $value) {
                $arr = [
                    'dia' => $key,
                    'valor' => $value
                ];
                array_push($arrayMapeado, $arr);
            }
            return $arrayMapeado;
        } else if ($tipo == 'pedido') {
            foreach ($dados as $key => $value) {
                $arr = [
                    'dia' => $key,
                    'qtd' => $value
                ];
                array_push($arrayMapeado, $arr);
            }
            return $arrayMapeado;
        }
    }
}
