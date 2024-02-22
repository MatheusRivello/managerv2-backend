<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Coletor
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property string|null $identificador_unico
 * @property string|null $fcm_token
 * @property string|null $token
 * @property string|null $modelo
 * @property string|null $versaoAndroid
 * @property Carbon|null $dt_ultima_sincronismo
 * @property bool $status
 * @property string $teste
 *
 * @package App\Models
 */
class Coletor extends Model
{
	protected $table = 'coletor';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id' => 'int',
		'fk_empresa' => 'int',
		'status' => 'bool'
	];

	protected $dates = [
		'dt_ultima_sincronismo'
	];

	protected $hidden = [
		'fcm_token',
		'token'
	];

	protected $fillable = [
		'fk_empresa',
		'identificador_unico',
		'fcm_token',
		'token',
		'modelo',
		'versaoAndroid',
		'dt_ultima_sincronismo',
		'status',
		'teste'
	];
}
