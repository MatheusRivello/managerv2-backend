<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VendedorCliente
 * 
 * @property int $id_cliente
 * @property int $id_vendedor
 * 
 * @property Cliente $cliente
 * @property Vendedor $vendedor
 *
 * @package App\Models
 */
class VendedorCliente extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'vendedor_cliente';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'id_vendedor' => 'int'
	];

	protected $fillable = [
		'id_cliente',
		'id_vendedor'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function vendedor()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor');
	}

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
