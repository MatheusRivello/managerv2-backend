<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fornecedor
 * 
 * @property int $id
 * @property int $id_filial
 * @property string $id_retaguarda
 * @property string|null $razao_social
 * @property string|null $nome_fantasia
 * @property bool $status
 * 
 * @property Filial $filial
 * @property Collection|Produto[] $produtos
 *
 * @package App\Models
 */
class Fornecedor extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'fornecedor';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'id_filial',
		'id_retaguarda',
		'razao_social',
		'nome_fantasia',
		'status'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function produtos()
	{
		return $this->hasMany(Produto::class, 'id_fornecedor');
	}

	public function getRelacionamentosCount()
	{
		$soma = $this->filial()->count() +
			$this->produtos()->count();

		return $soma;
	}
}
