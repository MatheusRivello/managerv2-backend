<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FormaPagamento
 * 
 * @OA\Schema 
 * Propriedades da Forma de pagamento
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="id_retaguarda", type="string"),
 * @OA\Property(property="descricao", type="string"),
 * 
 * @property int $id
 * @property string $id_retaguarda
 * @property string $descricao
 * @property float|null $valor_min
 * @property int|null $situacao
 * @property bool $status
 * 
 * @property Collection|ClienteFormaPgto[] $cliente_forma_pgtos
 * @property Collection|FormaPrazoPgto[] $forma_prazo_pgtos
 * @property Collection|Pedido[] $pedidos
 * @property Collection|TituloFinanceiro[] $titulo_financeiros
 *
 * @package App\Models
 */
class FormaPagamento extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'forma_pagamento';
	public $timestamps = false;

	protected $casts = [
		'valor_min' => 'float',
		'situacao' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'id',
		'id_retaguarda',
		'descricao',
		'valor_min',
		'situacao',
		'status'
	];

	public function cliente_forma_pgtos()
	{
		return $this->hasMany(ClienteFormaPgto::class, 'id_forma_pgto');
	}

	public function forma_prazo_pgtos()
	{
		return $this->hasMany(FormaPrazoPgto::class, 'id_forma_pgto');
	}

	public function pedidos()
	{
		return $this->hasMany(Pedido::class, 'id_forma_pgto');
	}

	public function titulo_financeiros()
	{
		return $this->hasMany(TituloFinanceiro::class, 'id_forma_pgto');
	}

	public function getRelacionamentosCount()
    {
		$soma = $this->cliente_forma_pgtos()->count() + 
		$this->forma_prazo_pgtos()->count() +
		$this->pedidos()->count() +
		$this->titulo_financeiros()->count();
		
        return $soma;
    }
}
