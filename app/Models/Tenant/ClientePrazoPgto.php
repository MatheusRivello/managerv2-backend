<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientePrazoPgto
 * 
 * @property int $id_cliente
 * @property int $id_prazo_pgto
 * 
 * @property Cliente $cliente
 * @property PrazoPagamento $prazo_pagamento
 *
 * @package App\Models
 */
class ClientePrazoPgto extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'cliente_prazo_pgto';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'id_prazo_pgto' => 'int'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function prazo_pagamento()
	{
		return $this->belongsTo(PrazoPagamento::class, 'id_prazo_pgto');
	}

	public function getRelacionamentosCount(){
		return $soma=0;
	}
}
