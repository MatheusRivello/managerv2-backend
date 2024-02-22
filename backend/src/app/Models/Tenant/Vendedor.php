<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vendedor
 * 
 * @property int $id
 * @property string $nome
 * @property bool $status
 * @property string|null $usuario
 * @property string|null $senha
 * @property int|null $supervisor
 * @property int|null $gerente
 * @property int|null $sequencia_pedido
 * @property bool|null $tipo
 * @property float|null $saldoVerba
 * 
 * @property Collection|Metum[] $meta
 * @property Collection|NotaFiscal[] $nota_fiscals
 * @property Collection|Pedido[] $pedidos
 * @property Collection|Rastro[] $rastros
 * @property Collection|Cliente[] $clientes
 * @property Collection|VendedorPrazo[] $vendedor_prazos
 * @property Collection|Produto[] $produtos
 * @property Collection|VendedorProtabelapreco[] $vendedor_protabelaprecos
 * @property Collection|Visita[] $visita
 *
 * @package App\Models
 */
class Vendedor extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'vendedor';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'status' => 'int',
		'supervisor' => 'int',
		'gerente' => 'int',
		'sequencia_pedido' => 'int',
		'tipo' => 'int',
		'saldoVerba' => 'float'
	];

	protected $fillable = [
		'id',
		'nome',
		'status',
		'usuario',
		'senha',
		'supervisor',
		'gerente',
		'sequencia_pedido',
		'tipo',
		'saldoVerba'
	];

	public function meta()
	{
		return $this->hasMany(Meta::class, 'id_vendedor');
	}

	public function nota_fiscals()
	{
		return $this->hasMany(NotaFiscal::class, 'id_vendedor');
	}

	public function pedidos()
	{
		return $this->hasMany(Pedido::class, 'id_vendedor');
	}

	public function rastros()
	{
		return $this->hasMany(Rastro::class, 'id_vendedor');
	}

	public function clientes()
	{
		return $this->belongsToMany(Cliente::class, 'vendedor_cliente', 'id_vendedor', 'id_cliente');
	}

	public function vendedor_prazos()
	{
		return $this->hasMany(VendedorPrazo::class, 'id_vendedor');
	}

	public function produtos()
	{
		return $this->belongsToMany(Produto::class, 'vendedor_produto', 'id_vendedor', 'id_produto');
	}

	public function supervisor()
	{
		return $this->hasMany(Vendedor::class, 'id', 'supervisor', 'id');
	}

	public function gerente()
	{
		return $this->hasMany(Vendedor::class, 'id', 'gerente', 'id');
	}

	public function vendedor_protabelaprecos()
	{
		return $this->hasMany(VendedorProtabelapreco::class, 'id_vendedor');
	}

	public function visita()
	{
		return $this->hasMany(Visita::class, 'id_vendedor');
	}
	public function cercaEletronica()
	{
		return $this->setConnection(\App\Services\Models\ConexaoTenantService::definirConexaoRelacionamento())->hasMany(Vendedor::class, 'id_vendedor');
	}

	public function getRelacionamentosCount()
	{
		$soma = $this->meta()->count() +
			$this->nota_fiscals()->count() +
			$this->pedidos()->count() +
			$this->rastros()->count() +
			$this->vendedor_prazos()->count() +
			$this->supervisor()->count() +
			$this->gerente()->count() +
			$this->vendedor_protabelaprecos()->count() +
			$this->visita()->count();

		return $soma;
	}
}
