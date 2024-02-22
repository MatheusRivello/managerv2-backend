<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProdutoEstoque
 * 
 * @property int $id
 * @property int $id_produto
 * @property string $unidade
 * @property float $quantidade
 * @property Carbon $dt_modificado
 * 
 * @property Produto $produto
 *
 * @package App\Models
 */
class ProdutoEstoque extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'produto_estoque';
	public $timestamps = false;

	protected $casts = [
		'id_produto' => 'int',
		'quantidade' => 'float'
	];

	protected $dates = [
		'dt_modificado'
	];

	protected $fillable = [
		'id_produto',
		'unidade',
		'quantidade',
		'dt_modificado'
	];

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}
	public function getRelacionamentosCount(){
		
	}
}
