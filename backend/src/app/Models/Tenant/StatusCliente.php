<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StatusCliente
 * 
 * @property int $id
 * @property string $id_retaguarda
 * @property string $descricao
 * @property bool $status
 * @property bool $bloqueia
 * 
 * @property Collection|Cliente[] $clientes
 *
 * @package App\Models
 */
class StatusCliente extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'status_cliente';
	public $timestamps = false;

	protected $casts = [
		'status' => 'bool',
		'bloqueia' => 'bool'
	];

	protected $fillable = [
		'id',
		'id_retaguarda',
		'descricao',
		'status',
		'bloqueia'
	];

	public function clientes()
	{
		return $this->hasMany(Cliente::class, 'id_status');
	}
	
	public function getRelacionamentosCount(){
		$soma=$this->clientes()->count();
		return $soma;
	}
}
