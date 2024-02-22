<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Api
 * 
 * @property int $id
 * @property string $prefix
 * @property string $url
 * @property string $descricao
 * 
 * @property Api|null $api
 * @property Collection|Api[] $api
 * @property Collection|Perfil[] $perfils
 *
 * @package App\Models
 */
class Api extends Model
{
	protected $table = 'api';
	public $connection = 'system';
	public $timestamps = false;

	protected $fillable = [
		'prefix',
		'descricao',
		'url'
	];

	public function perfils()
	{
		return $this->belongsToMany(Perfil::class, 'perfil_api', 'fk_api', 'fk_perfil');
	}
}
