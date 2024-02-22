<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CampanhaBeneficio
 * 
 * @property int $id_campanha
 * @property string $id_retaguarda
 * @property int|null $tipo
 * @property string|null $codigo
 * @property float|null $quantidade
 * @property float|null $percentual_desconto
 * @property int|null $desconto_automatico
 * @property int|null $bonificacao_automatica
 * @property int|null $status
 * 
 * @property Campanha $campanha
 *
 * @package App\Models
 */
class CampanhaBeneficio extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'campanha_beneficio';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_campanha' => 'int',
		'tipo' => 'int',
		'quantidade' => 'float',
		'percentual_desconto' => 'float',
		'desconto_automatico' => 'int',
		'bonificacao_automatica' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'tipo',
		'codigo',
		'quantidade',
		'percentual_desconto',
		'desconto_automatico',
		'bonificacao_automatica',
		'status'
	];

	public function campanha()
	{
		return $this->belongsTo(Campanha::class, 'id_campanha');
	}

	public function getRelacionamentosCount(){
		$soma=0;
		return $soma;
	}
}
