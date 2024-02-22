<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use App\Models\Tenant\JustificativaVisita;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JustificativaVendedor
 * 
 * @property int $id
 * @property int $id_empresa
 * @property string|null $descricao
 *
 * @package App\Models
 */
class JustificativaVendedor extends Model
{
	protected $table = 'justificativa_vendedor';
	protected $connection = 'system';

	public $timestamps = false;

	protected $casts = [
		'id_empresa' => 'int'
	];
	protected $fillable = [
		'id_empresa',
		'descricao'
	];
	public function justificativa_visita()
	{
		return $this->belongsTo(JustificativaVisita::class, 'id_justificativa');
	}

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
