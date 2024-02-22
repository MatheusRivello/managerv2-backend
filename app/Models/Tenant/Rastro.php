<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rastro
 * 
 * @property int $id
 * @property int $id_vendedor
 * @property Carbon|null $data
 * @property Carbon|null $hora
 * @property string|null $latitude
 * @property string|null $longitude
 * @property float|null $velocidade
 * @property float|null $altitude
 * @property string|null $direcao
 * @property string $mac
 * @property string|null $provedor
 * @property string|null $precisao
 * @property Carbon|null $dt_cadastro
 * @property bool|null $sinc_erp
 * 
 * @property Vendedor $vendedor
 *
 * @package App\Models
 */
class Rastro extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'rastro';
	public $timestamps = false;

	protected $casts = [
		'id_vendedor' => 'int',
		'velocidade' => 'float',
		'altitude' => 'float',
		'sinc_erp' => 'bool'
	];

	protected $dates = [
		'dt_cadastro'
	];

	protected $fillable = [
		'id_vendedor',
		'data',
		'hora',
		'latitude',
		'longitude',
		'velocidade',
		'altitude',
		'direcao',
		'mac',
		'provedor',
		'precisao',
		'dt_cadastro',
		'sinc_erp'
	];

	public function vendedor()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor');
	}

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
