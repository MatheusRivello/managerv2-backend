<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class IndicadorMargem
 * 
 * @property int $id
 * @property int $id_filial
 * @property bool $nivel
 * @property float $de
 * @property float $ate
 * @property int $indice
 * 
 * @property Filial $filial
 *
 * @package App\Models
 */
class IndicadorMargem extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'indicador_margem';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'nivel' => 'bool',
		'de' => 'float',
		'ate' => 'float',
		'indice' => 'int'
	];

	protected $fillable = [
		'id_filial',
		'nivel',
		'de',
		'ate',
		'indice'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function getRelacionamentosCount(){
		return $soma=0;
	}
}
