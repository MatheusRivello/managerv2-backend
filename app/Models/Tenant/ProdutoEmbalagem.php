<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProdutoEmbalagem
 * 
 * @property int $id
 * @property int $id_produto
 * @property string $unidade
 * @property string|null $embalagem
 * @property int $fator
 * @property bool $status
 * 
 * @property Produto $produto
 *
 * @package App\Models
 */
class ProdutoEmbalagem extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'produto_embalagem';
	public $timestamps = false;

	protected $casts = [
		'id_produto' => 'int',
		'fator' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'id_produto',
		'unidade',
		'embalagem',
		'fator',
		'status'
	];

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
