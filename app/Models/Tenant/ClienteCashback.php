<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClienteCashback
 * 
 * @property int $id
 * @property int $id_cliente
 * @property int $id_pedido
 * @property int $id_produto
 * @property float $cashback
 * @property Carbon $dt_compra
 * @property Carbon $dt_validade
 * 
 * @property Pedido $pedido
 * @property Cliente $cliente
 * @property Produto $produto
 *
 * @package App\Models
 */
class ClienteCashback extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'cliente_cashback';
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'id_pedido' => 'int',
		'id_produto' => 'int',
		'cashback' => 'float'
	];

	protected $dates = [
		'dt_compra',
		'dt_validade'
	];

	protected $fillable = [
		'id_cliente',
		'id_pedido',
		'id_produto',
		'cashback',
		'dt_compra',
		'dt_validade'
	];

	public function pedido()
	{
		return $this->belongsTo(Pedido::class, 'id_pedido');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}
}
