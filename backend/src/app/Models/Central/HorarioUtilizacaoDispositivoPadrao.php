<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HorarioUtilizacaoDispositivoPadrao
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property int $fk_horario
 * @property bool $status_padrao
 * 
 * @property Empresa $empresa
 * @property Horario $horario
 *
 * @package App\Models
 */
class HorarioUtilizacaoDispositivoPadrao extends Model
{
	protected $table = 'horario_utilizacao_dispositivo_padrao';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'fk_horario' => 'int',
		'status_padrao' => 'bool'
	];

	protected $fillable = [
		'fk_empresa',
		'fk_horario',
		'status_padrao'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function horario()
	{
		return $this->belongsTo(Horario::class, 'fk_horario');
	}
}
