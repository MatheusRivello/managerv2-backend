<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VendedorProduto
 * 
 * @property int $id_produto
 * @property int $id_vendedor
 * 
 * @property Produto $produto
 * @property Vendedor $vendedor
 *
 * @package App\Models
 */
class VendedorProduto extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'vendedor_produto';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_produto' => 'int',
		'id_vendedor' => 'int'
	];

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
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
