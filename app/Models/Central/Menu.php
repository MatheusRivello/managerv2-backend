<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 * 
 * @property int $id
 * @property int $fk_tipo_empresa
 * @property int|null $fk_menu
 * @property int|null $fk_tipo_permissao
 * @property string|null $classe
 * @property string $descricao
 * @property string $url
 * @property bool|null $personalizado
 * @property string|null $extra
 * @property int $ordem
 * @property bool $exibir_cabecalho
 * 
 * @property Menu|null $menu
 * @property TipoEmpresa $tipo_empresa
 * @property TipoPermissao|null $tipo_permissao
 * @property Collection|Menu[] $menus
 * @property Collection|Perfil[] $perfils
 *
 * @package App\Models
 */
class Menu extends Model
{
	protected $table = 'menu';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_tipo_empresa' => 'int',
		'fk_menu' => 'int',
		'fk_tipo_permissao' => 'int',
		'personalizado' => 'bool',
		'ordem' => 'int',
		'exibir_cabecalho' => 'bool'
	];

	protected $fillable = [
		'fk_tipo_empresa',
		'fk_menu',
		'fk_tipo_permissao',
		'classe',
		'descricao',
		'url',
		'personalizado',
		'extra',
		'ordem',
		'exibir_cabecalho'
	];

	public function menu()
	{
		return $this->belongsTo(Menu::class, 'fk_menu');
	}

	public function tipo_empresa()
	{
		return $this->belongsTo(TipoEmpresa::class, 'fk_tipo_empresa');
	}

	public function tipo_permissao()
	{
		return $this->belongsTo(TipoPermissao::class, 'fk_tipo_permissao');
	}

	public function menus()
	{
		return $this->hasMany(Menu::class, 'fk_menu');
	}

	public function perfils()
	{
		return $this->belongsToMany(Perfil::class, 'perfil_menu', 'fk_menu', 'fk_perfil');
	}
}
