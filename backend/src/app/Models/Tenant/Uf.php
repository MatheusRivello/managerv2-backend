<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Uf
 * 
 * @property string $id
 * @property int $id_regiao
 * @property string|null $descricao
 * 
 * @property Regiao $regiao
 *
 * @package App\Models
 */
class Uf extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'uf';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_regiao' => 'int'
	];

	protected $fillable = [
		'id_regiao',
		'descricao'
	];

	public function regiao()
	{
		return $this->belongsTo(Regiao::class, 'id_regiao');
	}
}
