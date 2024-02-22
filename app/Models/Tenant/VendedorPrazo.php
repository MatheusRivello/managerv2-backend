<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VendedorPrazo
 * 
 * @property int $id_filial
 * @property int $id_prazo_pgto
 * @property int $id_vendedor
 * 
 * @property Filial $filial
 * @property PrazoPagamento $prazo_pagamento
 * @property Vendedor $vendedor
 *
 * @package App\Models
 */
class VendedorPrazo extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'vendedor_prazo';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'id_prazo_pgto' => 'int',
		'id_vendedor' => 'int'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function prazo_pagamento()
	{
		return $this->belongsTo(PrazoPagamento::class, 'id_prazo_pgto');
	}

	public function vendedor()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor');
	}
}
