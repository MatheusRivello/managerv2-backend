<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TotalizadorDb
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property Carbon $data
 * @property float|null $tamanho_mb
 * 
 * @property Empresa $empresa
 *
 * @package App\Models
 */
class TotalizadorDb extends Model
{
	protected $table = 'totalizador_db';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'tamanho_mb' => 'float'
	];

	protected $dates = [
		'data'
	];

	protected $fillable = [
		'fk_empresa',
		'data',
		'tamanho_mb'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}
}
