<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoPerfil
 * 
 * @property int $id
 * @property string $descricao
 * @property int $fk_tipo_empresa
 * 
 * @property TipoEmpresa $tipo_empresa
 * @property Collection|Perfil[] $perfils
 *
 * @package App\Models
 */
class TipoPerfil extends Model
{
	protected $table = 'tipo_perfil';
	public $timestamps = false;

	protected $casts = [
		'fk_tipo_empresa' => 'int'
	];

	protected $fillable = [
		'descricao',
		'fk_tipo_empresa'
	];

	public function tipo_empresa()
	{
		return $this->belongsTo(TipoEmpresa::class, 'fk_tipo_empresa');
	}

	public function perfils()
	{
		return $this->hasMany(Perfil::class, 'fk_tipo_perfil');
	}
}
