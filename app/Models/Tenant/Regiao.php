<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Regiao
 * 
 * @property int $id
 * @property string $descricao
 * 
 * @property Collection|Uf[] $ufs
 *
 * @package App\Models
 */
class Regiao extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'regiao';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'descricao'
	];

	public function ufs()
	{
		return $this->hasMany(Uf::class, 'id_regiao');
	}

	public function getRelacionamentosCount(){
		$soma=$this->ufs()->count();
		return $soma;
	}
}
