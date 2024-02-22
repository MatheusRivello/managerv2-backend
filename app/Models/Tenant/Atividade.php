<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Atividade
 * 
 * @property int $id
 * @property int $id_filial
 * @property string $id_retaguarda
 * @property string $descricao
 * @property bool $status
 * 
 * @property Filial $filial
 *
 * @package App\Models
 */
class Atividade extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'atividade';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'id_filial',
		'id_retaguarda',
		'descricao',
		'status'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function getRelacionamentosCount()
	{
	}
}
