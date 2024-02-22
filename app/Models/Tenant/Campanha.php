<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Campanha
 * 
 * @property int $id
 * @property int|null $id_filial
 * @property string|null $id_retaguarda
 * @property string|null $descricao
 * @property int|null $tipo_modalidade
 * @property Carbon $data_inicial
 * @property Carbon $data_final
 * @property bool $permite_acumular_desconto
 * @property int|null $mix_minimo
 * @property float|null $valor_minimo
 * @property float|null $valor_maximo
 * @property float|null $volume_minimo
 * @property float|null $volume_maximo
 * @property int|null $qtd_max_bonificacao
 * @property int $status
 * 
 * @property Collection|CampanhaBeneficio[] $campanha_beneficios
 * @property Collection|CampanhaModalidade[] $campanha_modalidades
 * @property Collection|CampanhaParticipante[] $campanha_participantes
 * @property Collection|CampanhaRequisito[] $campanha_requisitos
 *
 * @package App\Models
 */
class Campanha extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'campanha';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'tipo_modalidade' => 'int',
		'permite_acumular_desconto' => 'bool',
		'mix_minimo' => 'int',
		'valor_minimo' => 'float',
		'valor_maximo' => 'float',
		'volume_minimo' => 'float',
		'volume_maximo' => 'float',
		'qtd_max_bonificacao' => 'int',
		'status' => 'int'
	];

	protected $dates = [
		'data_inicial',
		'data_final'
	];

	protected $fillable = [
		'id_filial',
		'id_retaguarda',
		'descricao',
		'tipo_modalidade',
		'data_inicial',
		'data_final',
		'permite_acumular_desconto',
		'mix_minimo',
		'valor_minimo',
		'valor_maximo',
		'volume_minimo',
		'volume_maximo',
		'qtd_max_bonificacao',
		'status'
	];

	public function campanha_beneficios()
	{
		return $this->hasMany(CampanhaBeneficio::class, 'id_campanha');
	}

	public function campanha_modalidades()
	{
		return $this->hasMany(CampanhaModalidade::class, 'id_campanha');
	}

	public function campanha_participantes()
	{
		return $this->hasMany(CampanhaParticipante::class, 'id_campanha');
	}

	public function campanha_requisitos()
	{
		return $this->hasMany(CampanhaRequisito::class, 'id_campanha');
	}

	public function getRelacionamentosCount()
	{
		$soma = $this->campanha_beneficios()->count() +
			$this->campanha_modalidades()->count() +
			$this->campanha_participantes()->count() +
			$this->campanha_requisitos()->count();
	}
}
