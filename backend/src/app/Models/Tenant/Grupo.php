<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Grupo
 * * @OA\Schema
 * Propriedades do Grupo
 * @var array
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="id_filial", type="integer"),
 * @OA\Property(property="id_retaguarda", type="string"),
 * @OA\Property(property="grupo_desc", type="string")
 * @OA\Property(property="descto_max", type="integer")
 * @OA\Property(property="status", type="integer")
 * 
 * @property int $id
 * @property int $id_filial
 * @property string $id_retaguarda
 * @property string $grupo_desc
 * @property float|null $descto_max
 * @property bool $status
 * 
 * @property Filial $filial
 * @property Collection|Produto[] $produtos
 *
 * @package App\Models
 */
class Grupo extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'grupo';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'descto_max' => 'float',
		'status' => 'bool'
	];

	protected $fillable = [
		'id_filial',
		'id_retaguarda',
		'grupo_desc',
		'descto_max',
		'status'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function produtos()
	{
		return $this->hasMany(Produto::class, 'id_grupo_new');
	}

	public function getRelacionamentosCount()
	{

		$soma = $this->filial()->count() +
			$this->produtos()->count();

		return $soma;
	}
}
