<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClienteFormaPgto
 * 
 * @property int $id_cliente
 * @property int $id_forma_pgto
 * 
 * @property Cliente $cliente
 * @property FormaPagamento $forma_pagamento
 *
 * @package App\Models
 */
class ClienteFormaPgto extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'cliente_forma_pgto';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'id_forma_pgto' => 'int'
	];

	protected $fillable = [
		'id_cliente',
		'id_forma_pgto',
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function forma_pagamento()
	{
		return $this->belongsTo(FormaPagamento::class, 'id_forma_pgto');
	}
	
	public function getRelacionamentosCount(){
		
	}
}
