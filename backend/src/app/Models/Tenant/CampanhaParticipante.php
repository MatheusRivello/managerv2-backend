<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CampanhaParticipante
 * 
 * @property int $id_campanha
 * @property string $id_retaguarda
 * 
 * @property Campanha $campanha
 *
 * @package App\Models
 */
class CampanhaParticipante extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'campanha_participante';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_campanha' => 'int'
	];

	public function campanha()
	{
		return $this->belongsTo(Campanha::class, 'id_campanha');
	}
}
