<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Perfil
 * 
 * @property int $id
 * @property string $descricao
 * @property int|null $fk_empresa
 * @property int $fk_tipo_perfil
 * @property int $fk_tipo_empresa
 * @property Carbon|null $dt_cadastro
 * @property Carbon|null $dt_modificado
 * @property bool $status
 * 
 * @property Empresa|null $empresa
 * @property TipoEmpresa $tipo_empresa
 * @property TipoPerfil $tipo_perfil
 * @property Collection|Menu[] $menus
 * @property Collection|Api[] $apis
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Perfil extends Model
{
	protected $table = 'perfil';
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_empresa' => 'int',
		'fk_tipo_perfil' => 'int',
		'fk_tipo_empresa' => 'int',
		'status' => 'bool'
	];

	protected $dates = [
		'dt_cadastro',
		'dt_modificado'
	];

	protected $fillable = [
		'descricao',
		'fk_empresa',
		'fk_tipo_perfil',
		'fk_tipo_empresa',
		'dt_cadastro',
		'dt_modificado',
		'status'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}

	public function tipo_empresa()
	{
		return $this->belongsTo(TipoEmpresa::class, 'fk_tipo_empresa');
	}

	public function tipo_perfil()
	{
		return $this->belongsTo(TipoPerfil::class, 'fk_tipo_perfil');
	}

	public function menus()
	{
		return $this->belongsToMany(Menu::class, 'perfil_menu', 'fk_perfil', 'fk_menu');
	}

	public function url_api()
	{
		return $this->belongsToMany(Api::class, 'perfil_api', 'fk_perfil', 'fk_api');
	}

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'fk_perfil');
	}
}
