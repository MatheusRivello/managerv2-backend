<?php

namespace App\Http\Controllers\api\v1\Relatorios;

use App\Http\Controllers\Controller;
use App\Services\Relatorios\Pedido;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/api/relatorios/pedido",
 *     summary="Lista o relatório de pedidos do vendedor",
 *     description="Lista o relatório de pedidos baseado nos filtrados passados.",
 *     operationId="Lista o relatório de pedido",
 *     tags={"Relatórios"},
 *     @OA\Parameter(
 *         name="idVendedor",
 *         in="query",
 *         description="Ids dos vendedores que devm ser colocados no relatório.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos vendedores")
 *     ),
 *     @OA\Parameter(
 *         name="idCliente",
 *         in="query",
 *         description="Ids dos clientes referenciados aos pedidos.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos clientes")
 *     ),
 *     @OA\Parameter(
 *         name="idPrazoPgto",
 *         in="query",
 *         description="Ids dos prazos de pagamentos referenciados aos pedidos.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos clientes")
 *     ),
 *     @OA\Parameter(
 *         name="idFormaPgto",
 *         in="query",
 *         description="Ids das formas de pagamento referenciados aos pedidos.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos clientes")
 *     ),
 *     @OA\Parameter(
 *         name="idTabelaPreco",
 *         in="query",
 *         description="Ids das tabelas de preço referenciados aos pedidos.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID dos clientes")
 *     ),
 *     @OA\Parameter(
 *         name="idFilial",
 *         in="query",
 *         description="Ids das filiais referenciados aos pedidos.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Parameter(
 *         name="idTipoPedido",
 *         in="query",
 *         description="Ids dos tipos de pedido referenciados aos pedidos.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID das filiais")
 *     ),
 *     @OA\Parameter(
 *         name="idPedidoDispositivo",
 *         in="query",
 *         description="Ids dos pedidos do dispositivo referenciados aos vendedores.(obs:passar como array.)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="array com o ID Ids dos pedidos do dispositivo")
 *     ),
 *     @OA\Parameter(
 *         name="dataInicial",
 *         in="query",
 *         description="Data inicial dos pedidos a serem filtradas",
 *         required=false,
 *         @OA\Schema(type="string", example="2023-01-01", description="Data inicial das visitas a serem filtradas")
 *     ),
 *     @OA\Parameter(
 *         name="dataFinal",
 *         in="query",
 *         description="Data final dos pedidos a serem filtradas",
 *         required=false,
 *         @OA\Schema(type="string", example="2023-01-01", description="Data final das visitas a serem filtradas")
 *     ),
 *     @OA\Parameter(
 *         name="exibirItem",
 *         in="query",
 *         description="Se irá exibir os itens do pedido (0 = não, 1 = sim)",
 *         required=false,
 *         @OA\Schema(type="integer", example="1", description="Se irá exibir os itens do pedido")
 *     ),
 *     @OA\Parameter(
 *         name="paginate",
 *         in="query",
 *         description="Quantidade de registros por página",
 *         required=false,
 *         @OA\Schema(type="integer", example="3", description="Quantidade de registros por página")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Ids dos status do pedido (obs:passar como array)",
 *         required=false,
 *         @OA\Schema(type="integer", example="3", description="Quantidade de registros por página")
 *     ),
 *     @OA\Parameter(
 *         name="pedidoComImagem",
 *         in="query",
 *         description="Se o pedido deverá vir com a imagem do produto ou não(0=não, 1=sim)",
 *         required=false,
 *         @OA\Schema(type="integer", example="0", description="Se o pedido deverá vir com a imagem do produto ou não")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Devolve o relatório",
 *         @OA\JsonContent(
 *             @OA\Property(property="head", type="object",
 *                          @OA\Property(property="totIpi", type="number"),
 *                          @OA\Property(property="totST", type="number"),  
 *                          @OA\Property(property="totFrete", type="number"),
 *                          @OA\Property(property="totSeguro", type="number"),  
 *                          @OA\Property(property="totVerba", type="number"),
 *                          @OA\Property(property="totPesoBruto", type="number"),  
 *                          @OA\Property(property="totPesoLiquido", type="number"),
 *                          @OA\Property(property="totValor", type="number"),  
 *                          @OA\Property(property="totSincronizados", type="integer"),
 *                          @OA\Property(property="totPendenteAprovacao", type="integer"),  
 *                          @OA\Property(property="totAprovados", type="integer"),
 *                          @OA\Property(property="totReprovados", type="integer"),  
 *                          @OA\Property(property="totAguardandoSincronismo", type="integer"),
 *                          @OA\Property(property="totPedidos", type="integer")
 *                 ),   
 *             @OA\Property(property="pedidos", type="object", 
 *                  @OA\Property(property="current_page", type="integer"),
 *                  @OA\Property(property="data", type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="pedidoIdRetaguarda", type="string"),
 *                          @OA\Property(property="pedidoIdDispositivo", type="integer"),
 *                          @OA\Property(property="valorTotal", type="string"),
 *                          @OA\Property(property="valorSt", type="string"),
 *                          @OA\Property(property="valorFrete", type="string"),
 *                          @OA\Property(property="valorSeguro", type="string"),
 *                          @OA\Property(property="valorIpi", type="string"),
 *                          @OA\Property(property="dtInicial", type="string"),
 *                          @OA\Property(property="dtEmissao", type="string"),
 *                          @OA\Property(property="dtSincErp", type="string"),
 *                          @OA\Property(property="pesoBruto", type="string"),
 *                          @OA\Property(property="pesoLiquido", type="string"),
 *                          @OA\Property(property="qtdeItens", type="integer"),
 *                          @OA\Property(property="ip", type="string"),
 *                          @OA\Property(property="pedidoValorDesconto", type="string"),
 *                          @OA\Property(property="observacao", type="string"),
 *                          @OA\Property(property="previsaoEntrega", type="string"),
 *                          @OA\Property(property="tabelaPreco", type="string"),
 *                          @OA\Property(property="tipoPedido", type="string"),
 *                          @OA\Property(property="prazoPagamento", type="string"),
 *                          @OA\Property(property="formaPagamento", type="string"),
 *                          @OA\Property(property="filialRazaoSocial", type="string"),
 *                          @OA\Property(property="filialNomeFantasia", type="string"),
 *                          @OA\Property(property="valorVerba", type="integer"),
 *                          @OA\Property(property="vendedor", type="string"),
 *                          @OA\Property(property="vendedorId", type="integer"),
 *                          @OA\Property(property="clienteRazaoSocial", type="string"),
 *                          @OA\Property(property="clienteNomeFantasia", type="string"),
 *                          @OA\Property(property="status", type="string"),
 *                          @OA\Property(property="itens", type="array",
 *                                      @OA\Items(
 *                                         @OA\Property(property="descricaoProduto", type="string"),
 *                                         @OA\Property(property="idPedido", type="integer"),
 *                                         @OA\Property(property="numeroItem", type="integer"),
 *                                         @OA\Property(property="unidvenda", type="string"),
 *                                         @OA\Property(property="quantidade", type="integer"),
 *                                         @OA\Property(property="embalagem", type="string"),
 *                                         @OA\Property(property="idProduto", type="integer"),
 *                                         @OA\Property(property="valorVerba", type="integer"),
 *                                         @OA\Property(property="valorSt", type="string"),
 *                                         @OA\Property(property="valorIpi", type="string"),
 *                                         @OA\Property(property="valorFrete", type="string"),
 *                                         @OA\Property(property="valorSeguro", type="string"),
 *                                         @OA\Property(property="percentualDesconto", type="string"),
 *                                         @OA\Property(property="valorUnitario", type="string"),
 *                                         @OA\Property(property="valorTotal", type="string"),
 *                                         @OA\Property(property="pesBru", type="string"),
 *                                         @OA\Property(property="pesLiq", type="string"),
 *                                      ),),
 *                          @OA\Property(property="totPedidoItens", type="array",
 *                              @OA\Items(
 *                                  @OA\Property(property="totUnitario", type="number"),
 *                                  @OA\Property(property="totQtd", type="number"),
 *                                  @OA\Property(property="totIpi", type="number"),
 *                                  @OA\Property(property="totFrete", type="number"),
 *                                  @OA\Property(property="totSeguro", type="number"),
 *                                  @OA\Property(property="totVerba", type="number"),
 *                                  @OA\Property(property="totPesBru", type="number"),
 *                                  @OA\Property(property="totPesLiq", type="number"),
 *                                  @OA\Property(property="totValor", type="number"),
 *                                )),),
 *                     ),
 *                   ),
 *                  
 *               ),
 *          ),
 *     
 *    @OA\Response(
 *        response=401,
 *        description="Não autorizado"
 *     ),
 *   ),   
 * ),
 **/
class PedidoController extends Controller
{

    private $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function getAll(Request $request)
    {
        return $this->pedido->assembleReport($request);
    }
}
