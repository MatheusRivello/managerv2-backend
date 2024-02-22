<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClienteTabelaGrupo
 * 
 * @property int $id
 * @property int $id_cliente
 * @property string $id_tabela
 * @property string $id_grupo
 * 
 * @property Cliente $cliente
 *
 * @package App\Models
 */
class ClienteTabelaGrupo extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'cliente_tabela_grupo';
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int'
	];

	protected $fillable = [
		'id_cliente',
		'id_tabela',
		'id_grupo'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function getRelacionamentosCount(){
		return $soma=0;
	}
}
