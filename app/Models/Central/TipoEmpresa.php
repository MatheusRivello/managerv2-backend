<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoEmpresa
 * 
 * @property int $id
 * @property string|null $descricao
 * 
 * @property Collection|Menu[] $menus
 * @property Collection|Perfil[] $perfils
 * @property Collection|TipoPerfil[] $tipo_perfils
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class TipoEmpresa extends Model
{
	protected $table = 'tipo_empresa';
	public $timestamps = false;
	public $connection = 'system';

	protected $fillable = [
		'descricao'
	];

	public function menus()
	{
		return $this->hasMany(Menu::class, 'fk_tipo_empresa');
	}

	public function perfils()
	{
		return $this->hasMany(Perfil::class, 'fk_tipo_empresa');
	}

	public function tipo_perfils()
	{
		return $this->hasMany(TipoPerfil::class, 'fk_tipo_empresa');
	}

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'fk_tipo_empresa');
	}
}
