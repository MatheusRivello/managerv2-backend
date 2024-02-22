<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema 
 * Propriedades da Meta
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="id_filial", type="integer"),
 * @OA\Property(property="id_vendedor", type="integer"),
 * @OA\Property(property="id_retaguarda", type="string"),
 * @OA\Property(property="descricao", type="string"),
 * @OA\Property(property="tot_qtd_ven", type="float"),
 * @OA\Property(property="tot_peso_ven", type="float"),
 * @OA\Property(property="objetivo_vendas", type="float"),
 * @OA\Property(property="tot_val_ven", type="float"),
 * @OA\Property(property="percent_atingido", type="float")
 * 
 * Class Meta
 * 
 * @property int $id
 * @property int $id_filial
 * @property int $id_vendedor
 * @property string $id_retaguarda
 * @property string $descricao
 * @property float|null $tot_qtd_ven
 * @property float|null $tot_peso_ven
 * @property float|null $objetivo_vendas
 * @property float|null $tot_val_ven
 * @property float|null $percent_atingido
 * 
 * @property Filial $filial
 * @property Vendedor $vendedor
 * @property Collection|MetaDetalhe[] $meta_detalhes
 *
 * @package App\Models
 */
class Meta extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'meta';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'id_vendedor' => 'int',
		'tot_qtd_ven' => 'float',
		'tot_peso_ven' => 'float',
		'objetivo_vendas' => 'float',
		'tot_val_ven' => 'float',
		'percent_atingido' => 'float'
	];

	protected $fillable = [
		'id_filial',
		'id_vendedor',
		'id_retaguarda',
		'descricao',
		'tot_qtd_ven',
		'tot_peso_ven',
		'objetivo_vendas',
		'tot_val_ven',
		'percent_atingido'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function vendedor()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor');
	}

	public function meta_detalhes()
	{
		return $this->hasMany(MetaDetalhe::class, 'id_meta');
	}

	public function getRelacionamentosCount(){
		$soma=$this->meta_detalhes()->count();
		return $soma;
	}
}
