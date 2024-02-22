<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StatusProduto
 * 
 * @property int $id
 * @property string $id_retaguarda
 * @property string $descricao
 * @property string|null $cor
 * @property bool $status
 *
 * @package App\Models
 */
class StatusProduto extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'status_produto';
	public $timestamps = false;

	protected $casts = [
		'status' => 'bool'
	];

	protected $fillable = [
		'id_retaguarda',
		'descricao',
		'cor',
		'status'
	];
	
	public function produto()
	{
		return $this->hasMany(Produto::class, 'status');
	}

	public function getRelacionamentosCount()
	{	
		$soma=$this->produto()->count();
		return $soma;
	}
}
