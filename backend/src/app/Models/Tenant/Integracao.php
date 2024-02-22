<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Integracao
 * 
 * @property int $id
 * @property int $integrador
 * @property int|null $tipo
 * @property int $id_interno
 * @property string|null $id_externo
 * @property string|null $campo_extra_1
 * @property string|null $campo_extra_2
 * @property string|null $campo_extra_3
 * @property string|null $ultimo_status
 * @property Carbon|null $dt_modificado
 *
 * @package App\Models
 */
class Integracao extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'integracao';
	public $timestamps = false;

	protected $casts = [
		'integrador' => 'int',
		'tipo' => 'int',
		'id_interno' => 'int'
	];

	protected $dates = [
		'dt_modificado'
	];

	protected $fillable = [
		'integrador',
		'tipo',
		'id_interno',
		'id_externo',
		'campo_extra_1',
		'campo_extra_2',
		'campo_extra_3',
		'ultimo_status',
		'dt_modificado'
	];

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
