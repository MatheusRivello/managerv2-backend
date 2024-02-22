<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * @OA\Schema 
 * Propriedades do Log
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="tipo", type="string"),
 * @OA\Property(property="id_empresa", type="integer"),
 * @OA\Property(property="mac", type="string"),
 * @OA\Property(property="id_cliente", type="integer"),
 * @OA\Property(property="id_filial", type="integer"),
 * @OA\Property(property="tabela", type="string"),
 * @OA\Property(property="conteudo", type="string"),
 * @OA\Property(property="mensagem", type="string"),
 * @OA\Property(property="ip", type="string"),
 * @OA\Property(property="dt_cadastro", type="string"),
 * @property int $id
 * @property string $tipo
 * @property int|null $id_empresa
 * @property string|null $mac
 * @property int|null $id_cliente
 * @property int|null $id_filial
 * @property string|null $tabela
 * @property string|null $conteudo
 * @property string|null $mensagem
 * @property string|null $ip
 * @property Carbon $dt_cadastro
 * 
 * @property Filial|null $filial
 *
 * @package App\Models
 */
class Log extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}
	
	protected $table = 'log';
	public $timestamps = false;

	protected $casts = [
		'id_empresa' => 'int',
		'id_cliente' => 'int',
		'id_filial' => 'int'
	];

	protected $dates = [
		'dt_cadastro'
	];

	protected $fillable = [
		'id_empresa',
		'mac',
		'id_cliente',
		'id_filial',
		'tabela',
		'conteudo',
		'mensagem',
		'ip',
		'dt_cadastro'
	];

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function getRelacionamentosCount(){
		$soma=0;
		return $soma;
	}
}
