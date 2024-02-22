<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema
 * Propriedades do motivo,
 * @var array
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="id_filial", type="integer"),
 * @OA\Property(property="id_retaguarda", type="string"),
 * @OA\Property(property="descricao", type="string")
 * @OA\Property(property="tipo", type="integer")
 * @OA\Property(property="status", type="integer")
 * 
 * Class Motivo
 * 
 * @property int $id
 * @property int $id_filial
 * @property string|null $id_retaguarda
 * @property string $descricao
 * @property bool|null $tipo
 * @property bool $status
 * @property Filial $filial
 * 
 *
 * @package App\Models
 */
class Motivo extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'motivo';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'tipo' => 'bool',
		'status' => 'bool'
	];

	protected $fillable = [
		'id_filial',
		'id_retaguarda',
		'descricao',
		'tipo',
		'status'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
