<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FormaPrazoPgto
 * 
 * @property int $id_forma_pgto
 * @property int $id_prazo_pgto
 * 
 * @property FormaPagamento $forma_pagamento
 * @property PrazoPagamento $prazo_pagamento
 *
 * @package App\Models
 */
class FormaPrazoPgto extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'forma_prazo_pgto';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_forma_pgto' => 'int',
		'id_prazo_pgto' => 'int'
	];

	protected $fillable = [
		'id_forma_pgto',
		'id_prazo_pgto'
	];

	public function forma_pagamento()
	{
		return $this->belongsTo(FormaPagamento::class, 'id_forma_pgto');
	}

	public function prazo_pagamento()
	{
		return $this->belongsTo(PrazoPagamento::class, 'id_prazo_pgto');
	}

	public function getRelacionamentosCount(){
		return $soma=0;
	}
}
