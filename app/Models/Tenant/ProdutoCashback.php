<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProdutoCashback
 * 
 * @property int $id
 * @property int $id_integrador
 * @property int $id_produto
 * @property float $cashback
 * @property Carbon $dt_modificado
 * 
 * @property Produto $produto
 *
 * @package App\Models
 */
class ProdutoCashback extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'produto_cashback';
	public $timestamps = false;

	protected $casts = [
		'id_integrador' => 'int',
		'id_produto' => 'int',
		'cashback' => 'float'
	];

	protected $dates = [
		'dt_modificado'
	];

	protected $fillable = [
		'id_integrador',
		'id_produto',
		'cashback',
		'dt_modificado'
	];

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}

	public function getRelacionamentosCount(){
		$soma=0;
		return $this->soma;
	}
}
