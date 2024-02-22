<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Referencia
 * 
 * @property int $id
 * @property int $sequencia
 * @property string|null $fornecedor
 * @property string|null $telefone
 * @property string|null $desde
 * @property string|null $conceito
 * @property float|null $limite
 * @property bool|null $pontual
 * @property float|null $ultima_fatura_valor
 * @property string|null $ultima_fatura_data
 * @property float|null $maior_fatura_valor
 * @property string|null $maior_fatura_data
 * @property float|null $maior_acumulo_valor
 * @property string|null $maior_acumulo_data
 * 
 * @property Collection|ClienteReferencia[] $cliente_referencia
 *
 * @package App\Models
 */
class Referencia extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'referencia';
	public $timestamps = false;

	protected $casts = [
		'sequencia' => 'int',
		'limite' => 'float',
		'pontual' => 'bool',
		'ultima_fatura_valor' => 'float',
		'maior_fatura_valor' => 'float',
		'maior_acumulo_valor' => 'float'
	];

	protected $fillable = [
		'sequencia',
		'fornecedor',
		'telefone',
		'desde',
		'conceito',
		'limite',
		'pontual',
		'ultima_fatura_valor',
		'ultima_fatura_data',
		'maior_fatura_valor',
		'maior_fatura_data',
		'maior_acumulo_valor',
		'maior_acumulo_data'
	];

	public function cliente_referencia()
	{
		return $this->hasMany(ClienteReferencia::class, 'id_referencia');
	}
}
