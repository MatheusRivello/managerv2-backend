<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogSincronismo
 * 
 * @property int $id
 * @property int $id_filial
 * @property int $tipo
 * @property int $status
 * @property Carbon $data_cad
 * @property int $usuario
 *
 * @package App\Models
 */
class LogSincronismo extends Model
{
	protected $table = 'log_sincronismo';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id_filial' => 'int',
		'tipo' => 'int',
		'status' => 'int',
		'usuario' => 'int'
	];

	protected $dates = [
		'data_cad'
	];

	protected $fillable = [
		'id_filial',
		'tipo',
		'status',
		'data_cad',
		'usuario'
	];
}
