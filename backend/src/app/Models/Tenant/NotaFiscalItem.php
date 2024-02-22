<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotaFiscalItem
 * 
 * @property int $id_filial
 * @property int $ped_num
 * @property int $id_produto
 * @property string|null $nfs_doc
 * @property string|null $nfs_serie
 * @property int $nfs_status
 * @property float $nfs_qtd
 * @property float $nfs_unitario
 * @property float|null $nfs_desconto
 * @property float|null $nfs_descto
 * @property float|null $nfs_total
 * @property float|null $ped_qtd
 * @property float|null $ped_total
 * @property float|null $nfs_custo
 * 
 * @property NotaFiscal $nota_fiscal
 * @property Produto $produto
 *
 * @package App\Models
 */
class NotaFiscalItem extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'nota_fiscal_item';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'ped_num' => 'int',
		'id_produto' => 'int',
		'nfs_status' => 'int',
		'nfs_qtd' => 'float',
		'nfs_unitario' => 'float',
		'nfs_desconto' => 'float',
		'nfs_descto' => 'float',
		'nfs_total' => 'float',
		'ped_qtd' => 'float',
		'ped_total' => 'float',
		'nfs_custo' => 'float'
	];

	protected $fillable = [
		'id_filial',
		'ped_num',
		'id_produto',
		'nfs_doc',
		'nfs_serie',
		'nfs_status',
		'nfs_qtd',
		'nfs_unitario',
		'nfs_desconto',
		'nfs_descto',
		'nfs_total',
		'ped_qtd',
		'ped_total',
		'nfs_custo'
	];

	public function nota_fiscal()
	{
		return $this->belongsTo(NotaFiscal::class, 'id_filial', 'id_filial');
	}

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}
}
