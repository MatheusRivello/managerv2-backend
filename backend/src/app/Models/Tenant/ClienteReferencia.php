<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClienteReferencium
 * 
 * @property int $id_cliente
 * @property int $id_referencia
 * 
 * @property Cliente $cliente
 * @property Referencium $referencium
 *
 * @package App\Models
 */
class ClienteReferencia extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'cliente_referencia';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'id_referencia' => 'int'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function referencium()
	{
		return $this->belongsTo(Referencium::class, 'id_referencia');
	}

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
