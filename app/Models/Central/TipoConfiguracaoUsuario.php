<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoConfiguracaoUsuario
 * 
 * @property int $id
 * @property string $descricao
 * @property string $titulo
 * @property string $subtitulo
 * 
 * @property Collection|ConfiguracaoUsuario[] $configuracao_usuarios
 *
 * @package App\Models
 */
class TipoConfiguracaoUsuario extends Model
{
	protected $table = 'tipo_configuracao_usuario';
	public $timestamps = false;
	public $connection = 'system';

	protected $fillable = [
		'descricao',
		'titulo',
		'subtitulo'
	];

	public function configuracao_usuarios()
	{
		return $this->hasMany(ConfiguracaoUsuario::class, 'fk_tipo_configuracao_usuario');
	}
}
