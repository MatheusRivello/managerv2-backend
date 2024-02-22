<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VendedorProtabelapreco
 * 
 * @property int $id_protabela_preco
 * @property int $id_vendedor
 * 
 * @property ProtabelaPreco $protabela_preco
 * @property Vendedor $vendedor
 *
 * @package App\Models
 */
class VendedorProtabelapreco extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'vendedor_protabelapreco';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_protabela_preco' => 'int',
		'id_vendedor' => 'int'
	];

	protected $fillable = [
		'id_protabela_preco',
		'id_vendedor'
	];

	public function protabela_preco()
	{
		return $this->belongsTo(ProtabelaPreco::class, 'id_protabela_preco');
	}

	public function vendedor()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor');
	}

	public function getRelacionamentosCount(){
		$soma=0;
		return $soma;
	}
}
