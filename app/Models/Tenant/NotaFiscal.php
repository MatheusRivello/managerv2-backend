<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NotaFiscal
 * @OA\Schema
 * Propriedades da Nota fiscal,
 * @var array
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="id_filial", type="integer"),
 * @OA\Property(property="filial", type="string"),
 * @OA\Property(property="cliente", type="string"),
 * @OA\Property(property="nome_fantasia", type="string"),
 * @OA\Property(property="vendedor", type="string"),
 * @OA\Property(property="ped_num", type="integer"),
 * @OA\Property(property="nfs_doc", type="string"),
 * @OA\Property(property="nfs_custo", type="number"),
 * @OA\Property(property="margem_ped", type="string"),
 * @OA\Property(property="markup_ped", type="string"),
 * @OA\Property(property="ped_total", type="number"),
 * @OA\Property(property="margem_nfs", type="string"),
 * @OA\Property(property="markup_nfs", type="string"),
 * @OA\Property(property="nfs_valbrut", type="number"),
 * @OA\Property(property="pedido_emissao", type="string"),
 * @OA\Property(property="nota_emissao", type="string"),
 * @OA\Property(property="nfs_status", type="integer"),
 * @OA\Property(property="nfs_tipo", type="string"),
 * @property int $id
 * @property int $id_filial
 * @property int $ped_num
 * @property int $id_cliente
 * @property int $id_vendedor
 * @property string|null $nfs_doc
 * @property string|null $nfs_serie
 * @property int $nfs_status
 * @property Carbon|null $nfs_emissao
 * @property float|null $nfs_valbrut
 * @property string $nfs_tipo
 * @property Carbon|null $ped_entrega
 * @property string|null $prazo_pag
 * @property string|null $forma_pag
 * @property Carbon|null $ped_emissao
 * @property float|null $ped_total
 * @property float|null $nfs_custo
 * @property string|null $observacao
 * 
 * @property Cliente $cliente
 * @property Filial $filial
 * @property Vendedor $vendedor
 * @property NotaFiscalItem $nota_fiscal_item
 *
 * @package App\Models
 */
class NotaFiscal extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'nota_fiscal';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'ped_num' => 'int',
		'id_cliente' => 'int',
		'id_vendedor' => 'int',
		'nfs_status' => 'int',
		'nfs_valbrut' => 'float',
		'ped_total' => 'float',
		'nfs_custo' => 'float'
	];

	protected $dates = [
		'nfs_emissao',
		'ped_entrega',
		'ped_emissao'
	];

	protected $fillable = [
		'id_filial',
		'ped_num',
		'id_cliente',
		'id_vendedor',
		'nfs_doc',
		'nfs_serie',
		'nfs_status',
		'nfs_emissao',
		'nfs_valbrut',
		'nfs_tipo',
		'ped_entrega',
		'prazo_pag',
		'forma_pag',
		'ped_emissao',
		'ped_total',
		'nfs_custo',
		'observacao'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function vendedor()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor');
	}

	public function nota_fiscal_item()
	{
		return $this->hasOne(NotaFiscalItem::class, 'id_filial', 'id_filial');
	}

	public function getRelacionamentosCount()
	{

		$soma = $this->cliente()->count() +
			$this->filial()->count() +
			$this->vendedor()->count() +
			$this->nota_fiscal_item()->count();
		return $soma;
	}
}
