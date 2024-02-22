<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MetaDetalhe
 * 
 * @property int $id
 * @property int $id_meta
 * @property int|null $ordem
 * @property string $descricao
 * @property int|null $tot_cli_cadastrados
 * @property int|null $tot_cli_atendidos
 * @property float|null $percent_tot_cli_atendidos
 * @property float|null $tot_qtd_ven
 * @property float|null $tot_peso_ven
 * @property float|null $percent_tot_peso_ven
 * @property float|null $tot_val_ven
 * @property float|null $percent_tot_val_ven
 * @property float|null $objetivo_vendas
 * @property float|null $percent_atingido
 * @property float|null $tendencia_vendas
 * @property float|null $percent_tendencia_ven
 * @property float|null $objetivo_clientes
 * @property float|null $numero_cli_falta_atender
 * @property float|null $ped_a_faturar
 * @property float|null $prazo_medio
 * @property float|null $percent_desconto
 * @property float|null $tot_desconto
 * 
 * @property Meta $Meta
 *
 * @package App\Models
 */
class MetaDetalhe extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'meta_detalhe';
	public $timestamps = false;

	protected $casts = [
		'id_meta' => 'int',
		'ordem' => 'int',
		'tot_cli_cadastrados' => 'int',
		'tot_cli_atendidos' => 'int',
		'percent_tot_cli_atendidos' => 'float',
		'tot_qtd_ven' => 'float',
		'tot_peso_ven' => 'float',
		'percent_tot_peso_ven' => 'float',
		'tot_val_ven' => 'float',
		'percent_tot_val_ven' => 'float',
		'objetivo_vendas' => 'float',
		'percent_atingido' => 'float',
		'tendencia_vendas' => 'float',
		'percent_tendencia_ven' => 'float',
		'objetivo_clientes' => 'float',
		'numero_cli_falta_atender' => 'float',
		'ped_a_faturar' => 'float',
		'prazo_medio' => 'float',
		'percent_desconto' => 'float',
		'tot_desconto' => 'float'
	];

	protected $fillable = [
		'id_meta',
		'ordem',
		'descricao',
		'tot_cli_cadastrados',
		'tot_cli_atendidos',
		'percent_tot_cli_atendidos',
		'tot_qtd_ven',
		'tot_peso_ven',
		'percent_tot_peso_ven',
		'tot_val_ven',
		'percent_tot_val_ven',
		'objetivo_vendas',
		'percent_atingido',
		'tendencia_vendas',
		'percent_tendencia_ven',
		'objetivo_clientes',
		'numero_cli_falta_atender',
		'ped_a_faturar',
		'prazo_medio',
		'percent_desconto',
		'tot_desconto'
	];

	public function Meta()
	{
		return $this->belongsTo(Meta::class, 'id_meta');
	}

	public function getRelacionamentosCount(){
		$soma=0;
		return $soma;
	}
}
