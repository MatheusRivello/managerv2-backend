<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pedido
 * @OA\Schema
 * Propriedades do pedido
 * @var array
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="idFilial", type="integer"),
 * @OA\Property(property="idRetaguarda", type="string"),
 * @OA\Property(property="idEndereco", type="string"),
 * @OA\Property(property="idCliente", type="integer"),
 * @OA\Property(property="idPedido_dispositivo", type="integer"),
 * @OA\Property(property="idTabela", type="integer"),
 * @OA\Property(property="idVendedor", type="integer"),
 * @OA\Property(property="idPrazoPgto", type="integer"),
 * @OA\Property(property="idFormaPgto", type="int"),
 * @OA\Property(property="idTipoPedido", type="integer"),
 * @OA\Property(property="supervisor", type="integer"),
 * @OA\Property(property="gerente", type="integer"),
 * @OA\Property(property="valorTotal", type="double"),
 * @OA\Property(property="qtdeItens", type="integer"),
 * @OA\Property(property="notaFiscal", type="string"),
 * @OA\Property(property="status", type="integer"),
 * @OA\Property(property="statusEntrega", type="integer"),
 * @OA\Property(property="placa", type="string"),
 * @OA\Property(property="valorSt", type="double"),
 * @OA\Property(property="valorIpi", type="double"),
 * @OA\Property(property="valorAcrescimo", type="double"),
 * @OA\Property(property="valorDesconto", type="double"),
 * @OA\Property(property="valorTotalComImposto", type="double"),
 * @OA\Property(property="valorTotalBruto", type="double"),
 * @OA\Property(property="valorVerba", type="double"),
 * @OA\Property(property="bonificacaoPorVerba", type="integer"),
 * @OA\Property(property="valorFrete", type="double"),
 * @OA\Property(property="valorSeguro", type="double"),
 * @OA\Property(property="margem", type="double"),
 * @OA\Property(property="obeservacao", type="string"),
 * @OA\Property(property="observacaoCliente", type="string"),
 * @OA\Property(property="previsaoEntrega", type="date"),
 * @OA\Property(property="pedidoOriginal", type="string"),
 * @OA\Property(property="latitude", type="string"),
 * @OA\Property(property="longitude", type="string"),
 * @OA\Property(property="precisao", type="string"),
 * @OA\Property(property="dtEntrega", type="date"),
 * @OA\Property(property="dtInicial", type="datetime"),
 * @OA\Property(property="dtEmissao", type="datetime"),
 * @OA\Property(property="dtSincErp", type="datetime"),
 * @OA\Property(property="dtCadastro", type="datetime"),
 * @OA\Property(property="origem", type="string"),
 * @OA\Property(property="notificacao_afv_manager", type="integer"),
 * @OA\Property(property="enviarEmail", type="integer"),
 * @OA\Property(property="ip", type="string"),
 * @OA\Property(property="mac", type="string"),
 * @OA\Property(property="autorizacaoDataEnviada", type="datetime"),
 * @OA\Property(property="autorizacaoSenha", type="string"),
 * @OA\Property(property="autorizacaoDataProcessada", type="datetime"),
 * @OA\Property(property="distanciaCliente", type="string"),
 * @OA\Property(property="motivoBloqueio", type="integer"),
 * @OA\Property(property="pesBru", type="double"),
 * @OA\Property(property="pesLiq", type="double"),
 * @OA\Property(property="nfsNum", type="string"),
 * @OA\Property(property="tipoFrete", type="integer"),
 * @OA\Property(property="idPedidoCliente", type="string")
 * 
 * @property int $id
 * @property int $id_filial
 * @property string|null $id_retaguarda
 * @property string|null $id_endereco
 * @property int $id_cliente
 * @property int|null $id_pedido_dispositivo
 * @property int $id_tabela
 * @property int|null $id_vendedor
 * @property int $id_prazo_pgto
 * @property int $id_forma_pgto
 * @property int $id_tipo_pedido
 * @property int|null $supervisor
 * @property int|null $gerente
 * @property float|null $valor_total
 * @property int|null $qtde_itens
 * @property string|null $nota_fiscal
 * @property bool|null $status
 * @property bool|null $status_entrega
 * @property string|null $placa
 * @property float|null $valor_st
 * @property float|null $valor_ipi
 * @property float|null $valor_acrescimo
 * @property float|null $valor_desconto
 * @property float $descto_financeiro
 * @property float|null $valorTotalComImpostos
 * @property float|null $valorTotalBruto
 * @property float|null $valorVerba
 * @property float|null $valor_frete
 * @property float|null $valor_seguro
 * @property float|null $margem
 * @property string|null $observacao
 * @property string|null $observacao_cliente
 * @property Carbon|null $previsao_entrega
 * @property string|null $pedido_original
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $precisao
 * @property Carbon|null $dt_entrega
 * @property Carbon|null $dt_inicial
 * @property Carbon|null $dt_emissao
 * @property Carbon|null $dt_sinc_erp
 * @property Carbon $dt_cadastro
 * @property string|null $origem
 * @property bool|null $notificacao_afv_manager
 * @property bool|null $enviarEmail
 * @property string|null $ip
 * @property string $mac
 * @property Carbon|null $autorizacaoDataEnviada
 * @property string|null $autorizacaoSenha
 * @property Carbon|null $autorizacaoaDataProcessada
 * @property string|null $distanciaCliente
 * @property int|null $motivoBloqueio
 * @property float|null $pes_bru
 * @property float|null $pes_liq
 * @property string|null $nfs_num
 * 
 * @property Cliente $cliente
 * @property Filial $filial
 * @property FormaPagamento $forma_pagamento
 * @property PrazoPagamento $prazo_pagamento
 * @property ProtabelaPreco $protabela_preco
 * @property TipoPedido $tipo_pedido
 * @property Vendedor|null $vendedor
 * @property Endereco|null $endereco
 * @property Collection|ClienteCashback[] $cliente_cashbacks
 * @property Collection|PedidoItem[] $pedido_items
 *
 * @package App\Models
 */
class Pedido extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'pedido';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'id_cliente' => 'int',
		'id_pedido_dispositivo' => 'int',
		'id_tabela' => 'int',
		'id_vendedor' => 'int',
		'id_prazo_pgto' => 'int',
		'id_forma_pgto' => 'int',
		'id_tipo_pedido' => 'int',
		'supervisor' => 'int',
		'gerente' => 'int',
		'valor_total' => 'float',
		'qtde_itens' => 'int',
		'status_entrega' => 'bool',
		'valor_st' => 'float',
		'valor_ipi' => 'float',
		'valor_acrescimo' => 'float',
		'valor_desconto' => 'float',
		'descto_financeiro' => 'float',
		'valorTotalComImpostos' => 'float',
		'valorTotalBruto' => 'float',
		'valorVerba' => 'float',
		'valor_frete' => 'float',
		'valor_seguro' => 'float',
		'margem' => 'float',
		'notificacao_afv_manager' => 'bool',
		'enviarEmail' => 'bool',
		'motivoBloqueio' => 'int',
		'pes_bru' => 'float',
		'pes_liq' => 'float'
	];

	protected $dates = [
		'previsao_entrega',
		'dt_entrega',
		'dt_inicial',
		'dt_emissao',
		'dt_sinc_erp',
		'dt_cadastro',
		'autorizacaoDataEnviada',
		'autorizacaoaDataProcessada'
	];

	protected $fillable = [
		'id_filial',
		'id_retaguarda',
		'id_endereco',
		'id_cliente',
		'id_pedido_dispositivo',
		'id_tabela',
		'id_vendedor',
		'id_prazo_pgto',
		'id_forma_pgto',
		'id_tipo_pedido',
		'supervisor',
		'gerente',
		'valor_total',
		'qtde_itens',
		'nota_fiscal',
		'status',
		'status_entrega',
		'placa',
		'valor_st',
		'valor_ipi',
		'valor_acrescimo',
		'valor_desconto',
		'descto_financeiro',
		'valorTotalComImpostos',
		'valorTotalBruto',
		'valorVerba',
		'valor_frete',
		'valor_seguro',
		'margem',
		'observacao',
		'observacao_cliente',
		'previsao_entrega',
		'pedido_original',
		'latitude',
		'longitude',
		'precisao',
		'dt_entrega',
		'dt_inicial',
		'dt_emissao',
		'dt_sinc_erp',
		'dt_cadastro',
		'origem',
		'notificacao_afv_manager',
		'enviarEmail',
		'ip',
		'mac',
		'autorizacaoDataEnviada',
		'autorizacaoSenha',
		'autorizacaoaDataProcessada',
		'distanciaCliente',
		'motivoBloqueio',
		'pes_bru',
		'pes_liq',
		'nfs_num'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function forma_pagamento()
	{
		return $this->belongsTo(FormaPagamento::class, 'id_forma_pgto');
	}

	public function prazo_pagamento()
	{
		return $this->belongsTo(PrazoPagamento::class, 'id_prazo_pgto');
	}

	public function protabela_preco()
	{
		return $this->belongsTo(ProtabelaPreco::class, 'id_tabela');
	}

	public function tipo_pedido()
	{
		return $this->belongsTo(TipoPedido::class, 'id_tipo_pedido');
	}

	public function vendedor()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor');
	}

	public function vendedor_info()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor')->select(array('id', 'nome'));
	}

	public function endereco()
	{
		return $this->belongsTo(Endereco::class, 'id_endereco');
	}

	public function cliente_cashbacks()
	{
		return $this->hasMany(ClienteCashback::class, 'id_pedido');
	}

	public function pedido_items()
	{
		return $this->hasMany(PedidoItem::class, 'id_pedido');
	}

	public function getRelacionamentosCount(){
		$soma=$this->cliente_cashbacks()->count()+
		$this->pedido_items()->count();
		return $soma;
	}
}
