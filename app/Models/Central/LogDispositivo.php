<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogDispositivo
 * 
 * @property int $id
 * @property string $mac
 * @property int $fk_empresa
 * @property Carbon|null $data
 * @property string|null $descricao
 * @property string|null $contexto
 * @property string|null $codigoErro
 * @property string|null $status
 * @property string|null $versaoApp
 * @property string|null $tipo
 * @property string $ip
 * @property Carbon $dt_cadastro
 * @property bool|null $resolvido
 * @property Carbon|null $dt_resolvido
 *
 * @package App\Models
 */
class LogDispositivo extends Model
{
	protected $table = 'log_dispositivo';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'resolvido' => 'bool'
	];

	protected $dates = [
		'data',
		'dt_cadastro',
		'dt_resolvido'
	];

	protected $fillable = [
		'mac',
		'fk_empresa',
		'data',
		'descricao',
		'contexto',
		'codigoErro',
		'status',
		'versaoApp',
		'tipo',
		'ip',
		'dt_cadastro',
		'resolvido',
		'dt_resolvido'
	];
}
