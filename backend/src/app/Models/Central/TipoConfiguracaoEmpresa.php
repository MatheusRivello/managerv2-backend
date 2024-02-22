<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoConfiguracaoEmpresa
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property bool $tipo
 * @property string $label
 * 
 * @property Collection|ConfiguracaoEmpresa[] $configuracao_empresas
 *
 * @package App\Models
 */
class TipoConfiguracaoEmpresa extends Model
{
	protected $table = 'tipo_configuracao_empresa';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'id' => 'int',
		'fk_empresa' => 'int',
		'tipo' => 'bool'
	];

	protected $fillable = [
		'label'
	];

	public function configuracao_empresas()
	{
		return $this->hasMany(ConfiguracaoEmpresa::class, 'fk_tipo_configuracao_empresa');
	}
}
