<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MotivoCercaEletronica
 * 
 * @property int $id
 * @property string $descricao
 * 
 * @property Collection|CercaEletronica[] $cerca_eletronicas
 *
 * @package App\Models
 */
class MotivoCercaEletronica extends Model
{
	protected $table = 'motivo_cerca_eletronica';
	public $timestamps = false;
	public $connection = 'system';

	protected $fillable = [
		'descricao'
	];

	public function cerca_eletronicas()
	{
		return $this->hasMany(CercaEletronica::class, 'fk_motivo_cerca_eletronica');
	}
}
