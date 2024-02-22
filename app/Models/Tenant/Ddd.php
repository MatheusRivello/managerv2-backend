<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ddd
 * 
 * @property int $id
 * @property string $id_uf
 * @property string|null $descricao
 *
 * @package App\Models
 */
class Ddd extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'ddd';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'id_uf',
		'descricao'
	];
}
