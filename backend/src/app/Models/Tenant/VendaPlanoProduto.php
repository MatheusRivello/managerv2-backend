<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VendaPlanoProduto
 * 
 * @property int $id
 * @property int|null $id_filial
 * @property int $id_cliente
 * @property int $id_produto
 * @property string $nfs_num
 * @property float $qtd_contratada
 * @property float $qtd_entregue
 * @property float $qtd_disponivel
 * @property float $valor_unitario
 * @property string $unidade
 * 
 * @property Cliente $cliente
 * @property Filial|null $filial
 * @property Produto $produto
 *
 * @package App\Models
 */
class VendaPlanoProduto extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'venda_plano_produto';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'id_filial' => 'int',
		'id_cliente' => 'int',
		'id_produto' => 'int',
		'qtd_contratada' => 'float',
		'qtd_entregue' => 'float',
		'qtd_disponivel' => 'float',
		'valor_unitario' => 'float'
	];

	protected $fillable = [
		'id',
		'id_filial',
		'id_cliente',
		'id_produto',
		'nfs_num',
		'qtd_contratada',
		'qtd_entregue',
		'qtd_disponivel',
		'valor_unitario',
		'unidade'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
