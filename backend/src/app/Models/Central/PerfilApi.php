<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PerfilApi
 * 
 * @property int $fk_perfil
 * @property int $fk_api
 * 
 * @property Api $api
 * @property Perfil $perfil
 *
 * @package App\Models
 */
class PerfilApi extends Model
{
	protected $table = 'perfil_api';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_perfil' => 'int',
		'fk_api' => 'int'
	];

	public function api()
	{
		return $this->belongsTo(Api::class, 'fk_api');
	}

	public function perfil()
	{
		return $this->belongsTo(Perfil::class, 'fk_perfil');
	}
}
