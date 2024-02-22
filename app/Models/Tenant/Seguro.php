<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Seguro
 * @OA\Schema 
 * Propriedades do Seguro
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="valor", type="number"),
 * @OA\Property(property="uf", type="string"),
 * @OA\Property(property="dt_modificado", type="string")
 * @property int $id
 * @property float $valor
 * @property string $uf
 * @property Carbon $dt_modificado
 *
 * @package App\Models
 */
class Seguro extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'seguro';
	public $timestamps = false;

	protected $casts = [
		'valor' => 'float'
	];

	protected $dates = [
		'dt_modificado'
	];

	protected $fillable = [
		'valor',
		'uf',
		'dt_modificado'
	];

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
