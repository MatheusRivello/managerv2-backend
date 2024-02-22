<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoPermissao
 * 
 * @property int $id
 * @property string $descricao
 * 
 * @property Collection|Menu[] $menus
 *
 * @package App\Models
 */
class TipoPermissao extends Model
{
	protected $table = 'tipo_permissao';
	public $timestamps = false;
	public $connection = 'system';

	protected $fillable = [
		'descricao'
	];

	public function menus()
	{
		return $this->hasMany(Menu::class, 'fk_tipo_permissao');
	}
}
