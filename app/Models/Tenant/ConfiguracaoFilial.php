<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConfiguracaoFilial
 * 
 * @property int $id
 * @property int $id_filial
 * @property string $descricao
 * @property string $valor
 * @property string $tipo
 * 
 * @property Filial $filial
 *
 * @package App\Models
 */
class ConfiguracaoFilial extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'configuracao_filial';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int'
	];

	protected $fillable = [
		'id_filial',
		'descricao',
		'valor',
		'tipo'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}
}
