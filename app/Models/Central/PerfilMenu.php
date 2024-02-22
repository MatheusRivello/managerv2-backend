<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PerfilMenu
 * 
 * @property int $fk_perfil
 * @property int $fk_menu
 * 
 * @property Menu $menu
 * @property Perfil $perfil
 *
 * @package App\Models
 */
class PerfilMenu extends Model
{
	protected $table = 'perfil_menu';
	public $incrementing = false;
	public $timestamps = false;
	public $connection = 'system';

	protected $casts = [
		'fk_perfil' => 'int',
		'fk_menu' => 'int'
	];

	public function menu()
	{
		return $this->belongsTo(Menu::class, 'fk_menu');
	}

	public function perfil()
	{
		return $this->belongsTo(Perfil::class, 'fk_perfil');
	}
}
