<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PrazoPagamento
 * @OA\Schema
 * Propriedades de prazo de pagamento
 * @var array
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="id_retaguarda", type="string"),
 * @OA\Property(property="descricao", type="string")
 * @OA\Property(property="variacao", type="float")
 * @OA\Property(property="valorMin", type="float")
 * @OA\Property(property="status", type="integer")
 * @property int $id
 * @property string $id_retaguarda
 * @property string $descricao
 * @property float $variacao
 * @property float|null $valor_min
 * @property bool $status
 * 
 * @property Collection|ClientePrazoPgto[] $cliente_prazo_pgtos
 * @property Collection|FormaPrazoPgto[] $forma_prazo_pgtos
 * @property Collection|Pedido[] $pedidos
 * @property Collection|VendedorPrazo[] $vendedor_prazos
 *
 * @package App\Models
 */
class PrazoPagamento extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'prazo_pagamento';
	public $timestamps = false;

	protected $casts = [
		'variacao' => 'float',
		'valor_min' => 'float',
		'status' => 'bool'
	];

	protected $fillable = [
		'id_retaguarda',
		'descricao',
		'variacao',
		'valor_min',
		'status'
	];

	public function cliente_prazo_pgtos()
	{
		return $this->hasMany(ClientePrazoPgto::class, 'id_prazo_pgto');
	}

	public function forma_prazo_pgtos()
	{
		return $this->hasMany(FormaPrazoPgto::class, 'id_prazo_pgto');
	}

	public function pedidos()
	{
		return $this->hasMany(Pedido::class, 'id_prazo_pgto');
	}

	public function vendedor_prazos()
	{
		return $this->hasMany(VendedorPrazo::class, 'id_prazo_pgto');
	}

	public function getRelacionamentosCount(){

		$soma=$this->cliente_prazo_pgtos()->count()+
		$this->forma_prazo_pgtos()->count()+
		$this->pedidos()->count()+
		$this->vendedor_prazos()->count();

		return $soma;
	}
}
