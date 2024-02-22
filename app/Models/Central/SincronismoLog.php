<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SincronismoLog
 * 
 * @property int $id_empresa
 * @property Carbon $data
 * 
 * @property Empresa $empresa
 *
 * @package App\Models
 */
class SincronismoLog extends Model
{
	protected $table = 'sincronismo_log';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id_empresa' => 'int'
	];

	protected $dates = [
		'data'
	];

	protected $fillable = [
		'id_empresa',
		'data'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'id_empresa');
	}
}
