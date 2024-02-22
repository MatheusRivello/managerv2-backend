<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HorarioUtilizacaoDispositivo
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property int $fk_horario
 * @property int $fk_dispositivo
 * @property int $id_vendedor
 * @property bool $status
 * 
 * @property Dispositivo $dispositivo
 * @property Empresa $empresa
 * @property Horario $horario
 *
 * @package App\Models
 */
class HorarioUtilizacaoDispositivo extends Model
{
	protected $table = 'horario_utilizacao_dispositivo';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id' => 'string',
		'fk_empresa' => 'string',
		'fk_horario' => 'string',
		'fk_dispositivo' => 'string',
		'id_vendedor' => 'string',
		'status' => 'string',
	];

	protected $fillable = [
		'fk_empresa',
		'fk_horario',
		'fk_dispositivo',
		'id_vendedor',
		'status',
	];

	public function dispositivo()
	{
		return $this->belongsTo(Dispositivo::class, 'fk_dispositivo');
	}

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function horario()
	{
		return $this->belongsTo(Horario::class, 'fk_horario');
	}
}
