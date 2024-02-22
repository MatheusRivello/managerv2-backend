<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProdutoDesctoQtd
 * 
 * @property int $id
 * @property int $id_produto
 * @property int $id_protabela_preco
 * @property int|null $quantidade
 * @property float|null $desconto
 * 
 * @property Produto $produto
 * @property ProtabelaPreco $protabela_preco
 *
 * @package App\Models
 */
class ProdutoDesctoQtd extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'produto_descto_qtd';
	public $timestamps = false;

	protected $casts = [
		'id' => 'string',
		'id_produto' => 'string',
		'id_protabela_preco' => 'string',
		'quantidade' => 'string',
		'desconto' => 'string'
	];

	protected $fillable = [
		'id_produto',
		'id_protabela_preco',
		'quantidade',
		'desconto'
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
