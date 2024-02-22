<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Relatorio
 * 
 * @property int $id
 * @property int|null $id_grupo
 * @property string|null $image
 * @property string $titulo
 * @property string $slug
 * @property int $tipo_grafico
 * @property bool $status
 * @property int $user_cad
 * @property Carbon $data_cad
 * @property int $user_alt
 * @property Carbon|null $data_alt
 * @property string $query
 * @property string $datakey
 * 
 * @property GrupoRelatorio|null $grupo_relatorio
 *
 * @package App\Models
 */
class Relatorio extends Model
{
	protected $table = 'relatorios';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id_grupo' => 'int',
		'tipo_grafico' => 'int',
		'status' => 'bool',
		'user_cad' => 'int',
		'user_alt' => 'int'
	];

	protected $dates = [
		'data_cad',
		'data_alt'
	];

	protected $fillable = [
		'id_grupo',
		'image',
		'titulo',
		'slug',
		'tipo_grafico',
		'status',
		'user_cad',
		'data_cad',
		'user_alt',
		'data_alt',
		'query',
		'datakey'
	];

	public function grupo_relatorio()
	{
		return $this->belongsTo(GrupoRelatorio::class, 'id_grupo');
	}

	public function tipo_grafico()
	{
		return $this->belongsTo(TipoGrafico::class, 'tipo_grafico');
	}
}
