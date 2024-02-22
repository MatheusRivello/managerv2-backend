<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProdutoIpi
 * 
 * @property int $id
 * @property int $id_produto
 * @property float|null $tipi_mva
 * @property float|null $tipi_mva_simples
 * @property float|null $tipi_mva_fe_nac
 * @property float|null $tipi_mva_fe_imp
 * @property int|null $tipi_tpcalc
 * @property float|null $tipi_aliquota
 * @property float|null $tipi_pauta
 * @property bool|null $calcula_ipi
 * 
 * @property Produto $produto
 *
 * @package App\Models
 */
class ProdutoIpi extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'produto_ipi';
	public $timestamps = false;

	protected $casts = [
		'id_produto' => 'int',
		'tipi_mva' => 'float',
		'tipi_mva_simples' => 'float',
		'tipi_mva_fe_nac' => 'float',
		'tipi_mva_fe_imp' => 'float',
		'tipi_tpcalc' => 'int',
		'tipi_aliquota' => 'float',
		'tipi_pauta' => 'float',
		'calcula_ipi' => 'bool'
	];

	protected $fillable = [
		'id_produto',
		'tipi_mva',
		'tipi_mva_simples',
		'tipi_mva_fe_nac',
		'tipi_mva_fe_imp',
		'tipi_tpcalc',
		'tipi_aliquota',
		'tipi_pauta',
		'calcula_ipi'
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
