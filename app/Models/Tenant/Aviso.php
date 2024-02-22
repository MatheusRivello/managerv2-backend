<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Aviso
 * 
 * @property int $id
 * @property int $id_filial
 * @property string $descricao
 * @property Carbon|null $dt_inicio
 * @property Carbon|null $dt_fim
 * @property bool|null $tipo
 * 
 * @property Filial $filial
 *
 * @package App\Models
 */
class Aviso extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'aviso';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'tipo' => 'bool'
	];

	protected $dates = [
		'dt_inicio',
		'dt_fim'
	];

	protected $fillable = [
		'id_filial',
		'descricao',
		'dt_inicio',
		'dt_fim',
		'tipo'
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
