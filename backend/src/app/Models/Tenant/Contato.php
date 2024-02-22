<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contato
 * 
 * @property int $id_cliente
 * @property bool $con_cod
 * @property string|null $telefone
 * @property string|null $email
 * @property string|null $nome
 * @property string|null $aniversario
 * @property string|null $hobby
 * @property bool|null $sinc_erp
 * 
 * @property Cliente $cliente
 *
 * @package App\Models
 */
class Contato extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'contato';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'con_cod' => 'int',
		'sinc_erp' => 'bool'
	];

	protected $fillable = [
		'id_cliente',
		'con_cod',
		'sinc_erp',
		'telefone',
		'email',
		'nome',
		'aniversario',
		'hobby',
		'sinc_erp'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function getRelacionamentosCount(){
		return $soma=0;
	}
}
