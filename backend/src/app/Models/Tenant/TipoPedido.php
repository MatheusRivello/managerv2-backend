<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoPedido 
 * @OA\Schema 
 * Propriedades do Tipo de pedido
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="idRetaguarda", type="string"),
 * @OA\Property(property="descricao", type="string"),
 * @property int $id
 * @property string $id_retaguarda
 * @property string $descricao
 * @property bool $status
 * 
 * @property Collection|Pedido[] $pedidos
 *
 * @package App\Models
 */
class TipoPedido extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'tipo_pedido';
	public $timestamps = false;

	protected $casts = [
		'status' => 'bool'
	];

	protected $fillable = [
		'id_retaguarda',
		'descricao',
		'status'
	];

	public function pedidos()
	{
		return $this->hasMany(Pedido::class, 'id_tipo_pedido');
	}

	public function getRelacionamentosCount(){
		$soma=$this->pedidos()->count();
		return $soma;
	}
}
