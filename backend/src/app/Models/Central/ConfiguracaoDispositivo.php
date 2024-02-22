<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConfiguracaoDispositivo
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property int $fk_dispositivo
 * @property int $fk_tipo_configuracao
 * @property string $valor
 * 
 * @property Dispositivo $dispositivo
 * @property Empresa $empresa
 * @property TipoConfiguracao $tipo_configuracao
 *
 * @package App\Models
 */
class ConfiguracaoDispositivo extends Model
{
	protected $table = 'configuracao_dispositivo';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'fk_dispositivo' => 'int',
		'fk_tipo_configuracao' => 'int'
	];

	protected $fillable = [
		'fk_empresa',
		'fk_dispositivo',
		'fk_tipo_configuracao',
		'valor'
	];

	public function dispositivo()
	{
		return $this->belongsTo(Dispositivo::class, 'fk_dispositivo');
	}

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function tipo_configuracao()
	{
		return $this->belongsTo(TipoConfiguracao::class, 'fk_tipo_configuracao');
	}
}
