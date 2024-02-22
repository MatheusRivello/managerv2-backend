<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConfiguracaoUsuario
 * 
 * @property int $id
 * @property int $fk_usuario
 * @property int $fk_tipo_configuracao_usuario
 * @property string $valor
 * 
 * @property TipoConfiguracaoUsuario $tipo_configuracao_usuario
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class ConfiguracaoUsuario extends Model
{
	protected $table = 'configuracao_usuario';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_usuario' => 'int',
		'fk_tipo_configuracao_usuario' => 'int'
	];

	protected $fillable = [
		'fk_usuario',
		'fk_tipo_configuracao_usuario',
		'valor'
	];

	public function tipo_configuracao_usuario()
	{
		return $this->belongsTo(TipoConfiguracaoUsuario::class, 'fk_tipo_configuracao_usuario');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'fk_usuario');
	}
}
