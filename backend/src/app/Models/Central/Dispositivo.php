<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Central;

use App\Models\Tenant\Vendedor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Dispositivo
 * 
 * @property int $id
 * @property int $fk_empresa
 * @property string|null $marca
 * @property string $mac
 * @property string|null $modelo
 * @property string|null $versaoApp
 * @property string|null $versao_android
 * @property string|null $imei
 * @property bool|null $licenca
 * @property string|null $id_unico
 * @property string|null $token_push
 * @property string $id_vendedor
 * @property bool $status
 * @property string|null $obs
 * @property Carbon|null $dt_cadastro
 * @property Carbon|null $dt_modificado
 * 
 * @property Empresa $empresa
 * @property Collection|ConfiguracaoDispositivo[] $configuracao_dispositivos
 * @property Collection|Horario[] $horarios
 *
 * @package App\Models
 */
class Dispositivo extends Model
{
	protected $table = 'dispositivo';
	protected $connection = 'system';
	public $timestamps = false;

	protected $casts = [
		'fk_empresa' => 'int',
		'licenca' => 'int',
		'status' => 'int'
	];

	protected $dates = [
		'dt_cadastro',
		'dt_modificado'
	];

	protected $hidden = [
		'id_unico',
		'token_push',
	];

	protected $fillable = [
		'fk_empresa',
		'marca',
		'mac',
		'password',
		'modelo',
		'versaoApp',
		'versao_android',
		'imei',
		'licenca',
		'id_unico',
		'token_push',
		'id_vendedor',
		'status',
		'obs',
		'dt_cadastro',
		'dt_modificado'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'fk_empresa');
	}
	
	public function vendedor()
	{
		return $this->setConnection(\App\Services\Models\ConexaoTenantService::definirConexaoRelacionamento())->belongsTo(Vendedor::class, 'id_vendedor');
	}

	public function configuracao_dispositivos()
	{
		return $this->hasMany(ConfiguracaoDispositivo::class, 'fk_dispositivo');
	}

	public function horarios()
	{
		return $this->belongsToMany(Horario::class, 'horario_utilizacao_dispositivo', 'fk_dispositivo', 'fk_horario')
					->withPivot('id', 'fk_empresa', 'id_vendedor', 'status');
	}
}
