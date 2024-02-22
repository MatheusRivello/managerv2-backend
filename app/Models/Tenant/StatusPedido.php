<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StatusPedido
 * 
 * @property int $id_filial
 * @property string $id_pedido
 * @property Carbon $data
 * @property string $status
 * @property Carbon|null $dt_cadastro
 * 
 * @property Filial $filial
 *
 * @package App\Models
 */
class StatusPedido extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'status_pedido';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int'
	];

	protected $dates = [
		'data',
		'dt_cadastro'
	];

	protected $fillable = [
		'id_filial',
		'id_pedido',
		'data',
		'status',
		'dt_cadastro'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}
}
