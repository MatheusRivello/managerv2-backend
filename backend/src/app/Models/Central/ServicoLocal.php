<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServicoLocal
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property Carbon $dt_atualizado
 * 
 * @property Empresa $empresa
 *
 * @package App\Models
 */
class ServicoLocal extends Model
{
	protected $table = 'servico_local';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int'
	];

	protected $dates = [
		'dt_atualizado'
	];

	protected $fillable = [
		'fk_empresa',
		'dt_atualizado'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}
}
