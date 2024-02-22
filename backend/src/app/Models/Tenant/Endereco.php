<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Endereco
 * 
 * @property string $id_retaguarda
 * @property int $id_cliente
 * @property bool $tit_cod
 * @property int $id_cidade
 * @property bool|null $sinc_erp
 * @property string|null $cep
 * @property string|null $logradouro
 * @property string|null $numero
 * @property string|null $complemento
 * @property string|null $bairro
 * @property string|null $uf
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $referencia
 * 
 * @property Cidade $cidade
 * @property Cliente $cliente
 * @property Collection|Pedido[] $pedidos
 *
 * @package App\Models
 */
class Endereco extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'endereco';
	protected $primaryKey = 'id_retaguarda';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'tit_cod' => 'bool',
		'id_cidade' => 'int',
		'sinc_erp' => 'bool',
		'numero' => 'int'
	];

	protected $fillable = [
		'id_retaguarda',
		'id_cliente',
		'tit_cod',
		'id_cidade',
		'sinc_erp',
		'cep',
		'logradouro',
		'numero',
		'complemento',
		'bairro',
		'uf',
		'latitude',
		'longitude',
		'referencia'
	];

	public function cidade()
	{
		return $this->belongsTo(Cidade::class, 'id_cidade');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function pedidos()
	{
		return $this->hasMany(Pedido::class, 'id_endereco');
	}

	public function getRelacionamentosCount(){
		$soma=$this->pedidos()->count();

		return $soma;
	}
}
