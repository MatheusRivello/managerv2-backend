<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MixProduto
 * 
 * @property int $id_produto
 * @property int $id_cliente
 * @property float|null $qtd_minima
 * @property float|null $qtd_faturada
 * 
 * @property Cliente $cliente
 * @property Produto $produto
 *
 * @package App\Models
 */
class MixProduto extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'mix_produto';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_produto' => 'int',
		'id_cliente' => 'int',
		'qtd_minima' => 'float',
		'qtd_faturada' => 'float'
	];

	protected $fillable = [
		'qtd_minima',
		'qtd_faturada'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}

	public function getRelacionamentosCount(){
		$soma=0;
		return $soma;
	}
}
