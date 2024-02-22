<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cidade
 * 
 * @property int $id
 * @property string $uf
 * @property string|null $descricao
 * @property string|null $codigo_ibge
 * @property string|null $ddd
 * 
 * @property Collection|Endereco[] $enderecos
 *
 * @package App\Models
 */
class Cidade extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'cidade';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'uf',
		'descricao',
		'codigo_ibge',
		'ddd'
	];

	public function enderecos()
	{
		return $this->hasMany(Endereco::class, 'id_cidade');
	}

	public function getRelacionamentosCount()
	{
		$soma = $this->enderecos()->count();
	}
}
