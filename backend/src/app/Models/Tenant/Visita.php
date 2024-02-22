<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Visita
 * @OA\Schema 
 * Propriedades da Visita
 * @OA\Property(property="id", type="integer"),
 * @OA\Property(property="id_filial", type="integer"),
 * @OA\Property(property="id_motivo", type="integer"),
 * @OA\Property(property="id_vendedor", type="integer"),
 * @OA\Property(property="id_cliente", type="integer"),
 * @OA\Property(property="id_pedido_dispositivo", type="integer"),
 * @OA\Property(property="status", type="boolean"),
 * @OA\Property(property="sinc_erp", type="boolean"),
 * @OA\Property(property="dt_marcada", type="string"),
 * @OA\Property(property="hora_marcada", type="string"),
 * @OA\Property(property="observacao", type="string"),
 * @OA\Property(property="ordem", type="integer"),
 * @OA\Property(property="latitude", type="string"),
 * @OA\Property(property="longitude", type="string"),
 * @OA\Property(property="precisao", type="string"),
 * @OA\Property(property="provedor", type="string"),
 * @OA\Property(property="lat_inicio", type="string"),
 * @OA\Property(property="lng_inicio", type="string"),
 * @OA\Property(property="lat_final", type="string"),
 * @OA\Property(property="lng_final", type="string"),
 * @OA\Property(property="precisao_inicio", type="string"),
 * @OA\Property(property="precisao_final", type="string"),
 * @OA\Property(property="hora_inicio", type="string"),
 * @OA\Property(property="hora_final", type="string"),
 * @OA\Property(property="dt_cadastro", type="string"),
 * @OA\Property(property="endereco_extenso_google", type="string")
 * 
 * @property int $id
 * @property int $id_filial
 * @property int|null $id_motivo
 * @property int $id_vendedor
 * @property int $id_cliente
 * @property int|null $id_pedido_dispositivo
 * @property bool $status
 * @property bool $sinc_erp
 * @property Carbon|null $dt_marcada
 * @property Carbon|null $hora_marcada
 * @property string|null $observacao
 * @property int|null $ordem
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $precisao
 * @property string|null $provedor
 * @property string|null $lat_inicio
 * @property string|null $lng_inicio
 * @property string|null $lat_final
 * @property string|null $lng_final
 * @property string|null $precisao_inicio
 * @property string|null $precisao_final
 * @property Carbon|null $hora_inicio
 * @property Carbon|null $hora_final
 * @property Carbon|null $dt_cadastro
 * @property string|null $endereco_extenso_google
 * 
 * @property Cliente $cliente
 * @property Filial $filial
 * @property Vendedor $vendedor
 *
 * @package App\Models
 */
class Visita extends Model
{
	public function __construct()
	{
		\App\Services\Models\ConexaoTenantService::definirConexaoTenant();
	}

	protected $table = 'visita';
	public $timestamps = false;

	protected $casts = [
		'id_filial' => 'int',
		'id_motivo' => 'int',
		'id_vendedor' => 'int',
		'id_cliente' => 'int',
		'id_pedido_dispositivo' => 'int',
		'sinc_erp' => 'bool',
		'ordem' => 'int'
	];

	protected $dates = [
		'dt_marcada',
		'hora_marcada',
		'hora_inicio',
		'hora_final',
		'dt_cadastro'
	];

	protected $fillable = [
		'id_filial',
		'id_motivo',
		'id_vendedor',
		'id_cliente',
		'id_pedido_dispositivo',
		'status',
		'sinc_erp',
		'dt_marcada',
		'hora_marcada',
		'observacao',
		'ordem',
		'latitude',
		'longitude',
		'precisao',
		'provedor',
		'lat_inicio',
		'lng_inicio',
		'lat_final',
		'lng_final',
		'precisao_inicio',
		'precisao_final',
		'hora_inicio',
		'hora_final',
		'dt_cadastro',
		'endereco_extenso_google',
		'agendado_erp'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function filial()
	{
		return $this->belongsTo(Filial::class, 'id_filial');
	}

	public function vendedor()
	{
		return $this->belongsTo(Vendedor::class, 'id_vendedor');
	}

	public function getRelacionamentosCount()
	{
		$soma = 0;
		return $soma;
	}
}
