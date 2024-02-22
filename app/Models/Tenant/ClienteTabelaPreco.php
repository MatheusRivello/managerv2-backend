<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClienteTabelaPreco
 * 
 * @property int $id_cliente
 * @property int $id_tabela
 *
 * @package App\Models
 */
class ClienteTabelaPreco extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'cliente_tabela_preco';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'id_tabela' => 'int'
	];

	protected $fillable = [
		'id_cliente',
		'id_tabela'
	];

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
