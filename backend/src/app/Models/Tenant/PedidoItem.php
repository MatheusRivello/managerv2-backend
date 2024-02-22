<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PedidoItem
 * 
 * @property int $id_pedido
 * @property int $numero_item
 * @property int $id_produto
 * @property int $id_tabela
 * @property string|null $embalagem
 * @property float|null $quantidade
 * @property float|null $valor_total
 * @property float|null $valor_st
 * @property float|null $valor_ipi
 * @property float|null $valor_tabela
 * @property float|null $valor_unitario
 * @property float|null $valor_desconto
 * @property float $cashback
 * @property float $unitario_cashback
 * @property float|null $valor_frete
 * @property float|null $valor_seguro
 * @property float|null $valorVerba
 * @property float|null $valorTotalComImpostos
 * @property float|null $valor_icms
 * @property float|null $ped_desqtd
 * @property float|null $percentualVerba
 * @property float|null $base_st
 * @property float|null $percentualdesconto
 * @property bool|null $tipoacrescimodesconto
 * @property bool|null $status
 * @property Carbon|null $dt_cadastro
 * @property string|null $unidvenda
 * @property float|null $custo
 * @property float|null $margem
 * @property float|null $pes_bru
 * @property float|null $pes_liq
 * 
 * @property Pedido $pedido
 * @property Produto $produto
 * @property ProtabelaPreco $protabela_preco
 *
 * @package App\Models
 */
class PedidoItem extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'pedido_item';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_pedido' => 'int',
		'numero_item' => 'int',
		'id_tabela' => 'int',
		'quantidade' => 'float',
		'valor_total' => 'float',
		'valor_st' => 'float',
		'valor_ipi' => 'float',
		'valor_tabela' => 'float',
		'valor_unitario' => 'float',
		'valor_desconto' => 'float',
		'cashback' => 'float',
		'unitario_cashback' => 'float',
		'valor_frete' => 'float',
		'valor_seguro' => 'float',
		'valorVerba' => 'float',
		'valorTotalComImpostos' => 'float',
		'valor_icms' => 'float',
		'ped_desqtd' => 'float',
		'percentualVerba' => 'float',
		'base_st' => 'float',
		'percentualdesconto' => 'float',
		'tipoacrescimodesconto' => 'bool',
		'status' => 'bool',
		'custo' => 'float',
		'margem' => 'float',
		'pes_bru' => 'float',
		'pes_liq' => 'float'
	];

	protected $dates = [
		'dt_cadastro'
	];

	protected $fillable = [
		'id_produto',
		'id_tabela',
		'embalagem',
		'quantidade',
		'valor_total',
		'valor_st',
		'valor_ipi',
		'valor_tabela',
		'valor_unitario',
		'valor_desconto',
		'cashback',
		'unitario_cashback',
		'valor_frete',
		'valor_seguro',
		'valorVerba',
		'valorTotalComImpostos',
		'valor_icms',
		'ped_desqtd',
		'percentualVerba',
		'base_st',
		'percentualdesconto',
		'tipoacrescimodesconto',
		'status',
		'dt_cadastro',
		'unidvenda',
		'custo',
		'margem',
		'pes_bru',
		'pes_liq'
	];

	public function pedido()
	{
		return $this->belongsTo(Pedido::class, 'id_pedido');
	}

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}

	public function protabela_preco()
	{
		return $this->belongsTo(ProtabelaPreco::class, 'id_tabela');
	}

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
