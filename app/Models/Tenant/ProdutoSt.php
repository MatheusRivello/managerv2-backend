<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProdutoSt
 * 
 * @property int $id
 * @property int $id_produto
 * @property string $tipo_contribuinte
 * @property string $uf
 * @property float|null $aliquota_icms
 * @property float|null $aliquota_icms_st
 * @property float|null $valor_referencia
 * @property int $class_pauta_mva
 * @property float|null $pauta
 * @property bool $tipo_mva
 * @property float|null $mva
 * @property float|null $reducao_icms
 * @property float|null $reducao_icms_st
 * @property string|null $modo_calculo
 * @property string|null $calcula_ipi
 * @property bool|null $frete_icms
 * @property bool|null $frete_ipi
 * @property bool|null $incide_ipi_base
 * 
 * @property Produto $produto
 *
 * @package App\Models
 */
class ProdutoSt extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'produto_st';
	public $timestamps = false;

	protected $casts = [
		'id_produto' => 'int',
		'aliquota_icms' => 'float',
		'aliquota_icms_st' => 'float',
		'valor_referencia' => 'float',
		'class_pauta_mva' => 'int',
		'pauta' => 'float',
		'tipo_mva' => 'bool',
		'mva' => 'float',
		'reducao_icms' => 'float',
		'reducao_icms_st' => 'float',
		'frete_icms' => 'bool',
		'frete_ipi' => 'bool',
		'incide_ipi_base' => 'bool'
	];

	protected $fillable = [
		'id_produto',
		'tipo_contribuinte',
		'uf',
		'aliquota_icms',
		'aliquota_icms_st',
		'valor_referencia',
		'class_pauta_mva',
		'pauta',
		'tipo_mva',
		'mva',
		'reducao_icms',
		'reducao_icms_st',
		'modo_calculo',
		'calcula_ipi',
		'frete_icms',
		'frete_ipi',
		'incide_ipi_base'
	];

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}

	public function getRelacionamentosCount(){
		$soma=0;
		return $soma;
	}
}
