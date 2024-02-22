<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TokenPassword
 * 
 * @property int $id_usuario
 * @property Carbon $data
 * 
 * @property Usuario $empresa
 *
 * @package App\Models
 */
class TokenPassword extends Model
{
	protected $table = 'token_password';
	protected $primaryKey = 'id';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id_usuario' => 'int',
	];

	protected $dates = [
		'data_cad'
	];

	protected $fillable = [
		'id_usuario',
		'token',
		'status',
		'data'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'id_usuario');
	}
}
