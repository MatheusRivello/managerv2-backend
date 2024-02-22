<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VendaPlano
 * 
 * @property int $id
 * @property int $id_filial
 * @property int $id_cliente
 * @property string $nfs_num
 * @property string|null $nfs_serie
 * @property string $nfs_doc
 * @property Carbon|null $nfs_emissao
 * @property string $tipo_saida
 * 
 * @property Cliente $cliente
 * @property Filial $filial
 *
 * @package App\Models
 */
class VendaPlano extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'venda_plano';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'id_cliente' => 'int'
	];

	protected $dates = [
		'nfs_emissao'
	];

	protected $fillable = [
		'id_filial',
		'id_cliente',
		'nfs_num',
		'nfs_serie',
		'nfs_doc',
		'nfs_emissao',
		'tipo_saida'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

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
