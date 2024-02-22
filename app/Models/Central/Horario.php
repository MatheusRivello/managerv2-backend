<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Horario
 * 
 * @property int $id
 * @property string|null $seg_i
 * @property string|null $seg_f
 * @property string|null $ter_i
 * @property string|null $ter_f
 * @property string|null $qua_i
 * @property string|null $qua_f
 * @property string|null $qui_i
 * @property string|null $qui_f
 * @property string|null $sex_i
 * @property string|null $sex_f
 * @property string|null $sab_i
 * @property string|null $sab_f
 * @property string|null $dom_i
 * @property string|null $dom_f
 * @property bool|null $status_seg
 * @property bool|null $status_ter
 * @property bool|null $status_qua
 * @property bool|null $status_qui
 * @property bool|null $status_sex
 * @property bool|null $status_sab
 * @property bool|null $status_dom
 * 
 * @property Collection|Dispositivo[] $dispositivos
 * @property Collection|HorarioUtilizacaoDispositivoPadrao[] $horario_utilizacao_dispositivo_padraos
 *
 * @package App\Models
 */
class Horario extends Model
{
	protected $table = 'horario';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'status_seg' => 'bool',
		'status_ter' => 'bool',
		'status_qua' => 'bool',
		'status_qui' => 'bool',
		'status_sex' => 'bool',
		'status_sab' => 'bool',
		'status_dom' => 'bool'
	];

	protected $fillable = [
		'seg_i',
		'seg_f',
		'ter_i',
		'ter_f',
		'qua_i',
		'qua_f',
		'qui_i',
		'qui_f',
		'sex_i',
		'sex_f',
		'sab_i',
		'sab_f',
		'dom_i',
		'dom_f',
		'status_seg',
		'status_ter',
		'status_qua',
		'status_qui',
		'status_sex',
		'status_sab',
		'status_dom'
	];

	public function dispositivos()
	{
		return $this->belongsToMany(Dispositivo::class, 'horario_utilizacao_dispositivo', 'fk_horario', 'fk_dispositivo')
					->withPivot('id', 'fk_empresa', 'id_vendedor', 'status');
	}

	public function horario_utilizacao_dispositivo_padraos()
	{
		return $this->hasMany(HorarioUtilizacaoDispositivoPadrao::class, 'fk_horario');
	}
}
