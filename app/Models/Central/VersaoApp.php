<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VersaoApp
 * 
 * @property int $codigo_versao
 * @property string $versao
 * @property bool $obrigatorio
 * @property string|null $observacao
 * @property Carbon $dt_cadastro
 *
 * @package App\Models
 */
class VersaoApp extends Model
{
	protected $table = 'versao_app';
	protected $primaryKey = 'codigo_versao';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'codigo_versao' => 'int',
		'obrigatorio' => 'bool'
	];

	protected $dates = [
		'dt_cadastro'
	];

	protected $fillable = [
		'versao',
		'obrigatorio',
		'observacao',
		'dt_cadastro'
	];
}
