<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CampanhaRequisito
 * 
 * @property int $id_campanha
 * @property string $id_retaguarda
 * @property int|null $tipo
 * @property string|null $codigo
 * @property float|null $quantidade
 * @property float|null $quantidade_max
 * @property float|null $percentual_desconto
 * @property int|null $obrigatorio
 * @property int|null $status
 * 
 * @property Campanha $campanha
 *
 * @package App\Models
 */
class CampanhaRequisito extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'campanha_requisito';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_campanha' => 'int',
		'tipo' => 'int',
		'quantidade' => 'float',
		'quantidade_max' => 'float',
		'percentual_desconto' => 'float',
		'obrigatorio' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'tipo',
		'codigo',
		'quantidade',
		'quantidade_max',
		'percentual_desconto',
		'obrigatorio',
		'status'
	];

	public function campanha()
	{
		return $this->belongsTo(Campanha::class, 'id_campanha');
	}
}
