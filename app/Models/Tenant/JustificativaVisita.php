<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use App\Models\Central\JustificativaVendedor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JustificativaVisita
 * 
 * @property int $id
 * @property int $id_justificativa
 * @property int $id_vendedor
 * @property Carbon|null $data_cadastro
 * @property Carbon $hora_ini
 * @property Carbon $hora_fim
 *
 * @package App\Models
 */
class JustificativaVisita extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'justificativa_visita';
	public $timestamps = false;

	protected $casts = [
		'id_justificativa' => 'int',
		'id_vendedor' => 'int'
	];

	protected $dates = [
		'data_cadastro'
	];

	protected $fillable = [
		'id_justificativa',
		'id_vendedor',
		'data_cadastro',
		'descricao',
		'hora_ini',
		'hora_fim'
	];

	public function motivo_ausencia()
	{
		return $this->hasMany(JustificativaVendedor::class, 'id');
	}

	public function getRelacionamentosCount(){
		$soma=$this->motivo_ausencia()->count();
		return $soma;
	}
}
