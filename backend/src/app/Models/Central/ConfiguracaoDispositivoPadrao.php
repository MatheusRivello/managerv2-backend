<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConfiguracaoDispositivoPadrao
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property int $fk_tipo_configuracao
 * @property string $valor
 * 
 * @property Empresa $empresa
 * @property TipoConfiguracao $tipo_configuracao
 *
 * @package App\Models
 */
class ConfiguracaoDispositivoPadrao extends Model
{
	protected $table = 'configuracao_dispositivo_padrao';
	public $timestamps = false;
	public $connection = 'system';
	
	protected $casts = [
		'fk_empresa' => 'int',
		'fk_tipo_configuracao' => 'int'
	];

	protected $fillable = [
		'fk_empresa',
		'fk_tipo_configuracao',
		'valor'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function tipo_configuracao()
	{
		return $this->belongsTo(TipoConfiguracao::class, 'fk_tipo_configuracao');
	}

	public function label_tipo_config()
	{
		return $this->belongsTo(TipoConfiguracao::class, 'fk_tipo_configuracao')->select('id', 'label');
	}
}
