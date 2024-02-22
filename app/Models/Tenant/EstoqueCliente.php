<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EstoqueCliente
 * 
 * @property int $id_filial
 * @property int $id_vendedor
 * @property int $id_cliente
 * @property int $id_produto
 * @property int $quantidade
 * @property float $valor_gondula
 * @property float $markup
 * @property Carbon $dt_coleta
 * 
 * @property Filial $filial
 * @property Cliente $cliente
 * @property Produto $produto
 * @property Vendedor $vendedor
 *
 * @package App\Models
 */
class EstoqueCliente extends Model
{
    public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'estoque_cliente';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'id_vendedor' => 'int',
		'id_cliente' => 'int',
		'id_produto' => 'int',
		'quantidade' => 'int',
		'valor_gondula' => 'float',
		'markup' => 'float'
	];

	protected $dates = [
		'dt_coleta'
	];

	protected $fillable = [
		'id_filial',
		'id_vendedor',
		'id_cliente',
		'id_produto',
		'quantidade',
		'valor_gondula',
		'markup',
		'dt_coleta'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function produto()
	{
		return $this->belongsTo(Produto::class, 'id_produto');
	}

	public function vendedor()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor');
	}
}
