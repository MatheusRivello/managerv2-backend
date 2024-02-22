<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogApi
 * 
 * @property int $id
 * @property string $uri
 * @property string $method
 * @property string|null $params
 * @property string $api_key
 * @property string $ip_address
 * @property int $time
 * @property float|null $rtime
 * @property string $authorized
 * @property int|null $response_code
 * @property string|null $response
 * @property Carbon|null $dt_cadastro
 *
 * @package App\Models
 */
class LogApi extends Model
{
	protected $table = 'log_api';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'time' => 'int',
		'rtime' => 'float',
		'response_code' => 'int'
	];

	protected $dates = [
		'dt_cadastro'
	];

	protected $fillable = [
		'uri',
		'method',
		'params',
		'api_key',
		'ip_address',
		'time',
		'rtime',
		'authorized',
		'response_code',
		'response',
		'dt_cadastro'
	];
}
