<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProtabelaIten
 * 
 * @property int $id_produto
 * @property int $id_protabela_preco
 * @property float|null $unitario
 * @property bool $status
 * @property float|null $qevendamax
 * @property float|null $qevendamin
 * @property float|null $desconto
 * @property float|null $desconto2
 * @property float|null $desconto3
 * 
 * @property Produto $produto
 * @property ProtabelaPreco $protabela_preco
 *
 * @package App\Models
 */
class ProtabelaIten extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'protabela_itens';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_produto' => 'string',
		'id_protabela_preco' => 'string',
		'status' => 'string',
	];

	protected $fillable = [
		'id_produto',
		'id_protabela_preco',
		'unitario',
		'status',
		'qevendamax',
		'qevendamin',
		'desconto',
		'desconto2',
		'desconto3'
	];

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}

	public function protabela_preco()
	{
		return $this->belongsTo(ProtabelaPreco::class, 'id_protabela_preco');
	}
}
