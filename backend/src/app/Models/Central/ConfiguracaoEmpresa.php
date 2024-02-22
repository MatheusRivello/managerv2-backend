<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConfiguracaoEmpresa
 * 
 * @property int $fk_empresa
 * @property int $fk_tipo_configuracao_empresa
 * @property bool $tipo
 * @property bool $valor
 * 
 * @property Empresa $empresa
 * @property TipoConfiguracaoEmpresa $tipo_configuracao_empresa
 *
 * @package App\Models
 */
class ConfiguracaoEmpresa extends Model
{
	protected $table = 'configuracao_empresa';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'fk_tipo_configuracao_empresa' => 'int',
		'tipo' => 'int',
		'valor' => 'int',
		'tipo_ordem' => 'int',
		'grupo' => 'int'
	];

	protected $fillable = [
		'valor'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function tipo_configuracao_empresa()
	{
		return $this->belongsTo(TipoConfiguracaoEmpresa::class, 'fk_tipo_configuracao_empresa');
	}

	public function tipoConfigSimplificada()
	{
		return $this->belongsTo(TipoConfiguracaoEmpresa::class, 'fk_tipo_configuracao_empresa')
					->select('id', 'label', 'tipo as tipo_ordem');
	}
}
